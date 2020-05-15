<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use Scaffolding;
use Form;
use Html;
use DB;
use App\Models\Settings;
use App\Models\Pages;
use Illuminate\Http\JsonResponse;
use Validator;

class SettingsController extends BackEndController
{

    /**
     * Get category list
     *
     * @return array
     */
    public function getPageList()
    {
        $Model = new \App\Models\Pages();
        $list = array("" => trans('main.select.category'));
        $result = $Model->get()->pluck("title", "id")->all();
        $list = $list + $result;
        return $list;
    }

    public function edit()
    {
        $settings = Settings::get();
        $pages = Pages::get_select();
        $parameters = $this->getParameters();
        $parameters['settings'] = $settings;
        $parameters['pages'] = $pages;
        // Render View
        return view('backend.themes.standard.settings', $parameters);
//        return $this->render($parameters);
    }

    public function update()
    {
        $Request = request();
        $request = $Request->all();
        // Validation
        $validation_rules = array(
            'homepage' => 'required|exists:pages,id',
            'logo' => 'file|mimetypes:image/png,image/jpeg|nullable',
            'image_size_small_width' => 'required|numeric',
            'image_size_small_height' => 'required|numeric',
            'image_size_medium_width' => 'required|numeric',
            'image_size_medium_width' => 'required|numeric',
        );
        $validator = Validator::make($request, $validation_rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        // Update settings
        unset($request['_method'], $request['_token'], $request['submit']);
        // Upload logo
        $filename = null;
        $hasImage = $Request->hasFile('logo');
        if ($hasImage) {
            $destinationPath = images_temporary_path();
            $file = $Request->file('logo');
            $fileExtension = $file->getClientOriginalExtension();
            $filename = get_unique_filename() . '.' . $fileExtension;
            $fullPath = $destinationPath . $filename;
            while (file_exists($fullPath)) {
                $filename = get_unique_filename() . '.' . $fileExtension;
                $fullPath = $destinationPath . $filename;
            }
            $status = $file->move($destinationPath, $filename); // uploading file to given path
            $Request->files->remove('logo');
            $request['logo'] = $filename;
        }
        $result = DB::transaction(function ($db) use ($request) {
                    foreach ($request as $key => $value) {
                        if ($key == "logo" && !$value) {
                            continue;
                        }
                        $parameters = array(
                            'value' => $value,
                        );
                        // Insert order
                        $SettingsModel = new Settings;
                        $setting = $SettingsModel->where('name', '=', $key)->update($parameters);
                    }
                    // Move logo
                    $hasImage = request()->hasFile('logo');
                    if ($hasImage) {
                        // Move image file to permanent directory
                        rename(images_temporary_path($request['logo']), images_path($request['logo']));
                        // Delete previous file
                        if ($request['logo_old']) {
                            unlink(images_path($request['logo_old']));
                        }
                    }
                });
        return back()->with('dk_settings_info_success', trans('dkscaffolding.notification.update.success'));
    }

    /**
     * Build posts page
     *
     * @return Illuminate\View\View
     */
    public function index()
    {
        // Get settings
        $Model = new Settings;
        $records = $Model->get();
        $settings = array();
        foreach ($records as $record) {
            $settings[$record->key] = $record->value;
        }
        // Get pages
        $Model = new Pages;
        $pages = $Model->where('status', '=', 1)->orderBy('id', 'ASC')->get();
        // Set parameters
        $parameters = $this->getParameters();
        $parameters['url'] = action(config('app.backend_namespace') . 'SettingController@save');
        $parameters['settings'] = $settings;
        $parameters['pages'] = $pages;
        // Render View
        return view('backend.themes.standard.settings', $parameters);
    }

    public function save()
    {
        $request = request();
        $parameters = $request->all();
        $rules = array(
            'homepage' => 'required|numeric',
        );
        $validator = Validator::make($parameters, $rules);
        // Send Response
        if ($validator->fails()) {
            if ($request->ajax()) {
                // Send Response
                $JsonResponse = new JsonResponse($validator->errors(), 400);
                $JsonResponse->send();
                exit;
            }
        }
        unset($parameters['_token'], $parameters['submit']);
        $result = DB::transaction(function ($db) use ($parameters) {
                    $menus = array();
                    foreach ($parameters as $key => $value) {
                        $params = array();
                        $params['value'] = $value;
                        $Model = new Settings;
                        $record = $Model->where('key', '=', $key)->update($params);
                    }
                });
        // Send Response
        if ($request->ajax()) {
            $JsonResponse = new JsonResponse(trans('main.notification.insert.success'), 200);
            $JsonResponse->send();
            exit;
        }
    }

}
