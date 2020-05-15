<?php

namespace App\Libraries\View;

use Exception;
use ErrorException;
use Illuminate\View\Compilers\CompilerInterface;
use Illuminate\View\Engines\CompilerEngine as OldCompilerEngine;

class CompilerEngine extends OldCompilerEngine
{

    /**
     * Get the evaluated contents of the view.
     *
     * @param  string  $path
     * @param  array   $data
     * @return string
     */
    public function get($path, array $data = [])
    {
        $this->lastCompiled[] = $path;

        // If this given view has expired, which means it has simply been edited since
        // it was last compiled, we will re-compile the views so we can evaluate a
        // fresh copy of the view. We'll pass the compiler the path of the view.
        if ($this->compiler->isExpired($path)) {
            $this->compiler->compile($path);
        }

        $compiled = $this->compiler->getCompiledPath($path);
        // Once we have the path to the compiled file, we will evaluate the paths with
        // typical PHP just like any other templates. We also keep a stack of views
        // which have been rendered for right exception messages to be generated.
        $results  = $this->evaluatePath($compiled, $data);
        if (config('view.template_path_hint')) {
            ob_start();
            ?>
            <div style="position: relative; border: 1px solid red; padding: 28px 0px;">
                <div style="position: absolute; top: 0px; background: red; color: #fff; width: 100%; padding: 5px; line-height: normal;"><?php echo $path; ?></div>
                <div style="padding: 0px 10px;"><?php echo $results; ?></div>
            </div>
            <?php
            $results = ob_get_contents();
            ob_end_clean();
        }
        array_pop($this->lastCompiled);

        return $results;
    }
}