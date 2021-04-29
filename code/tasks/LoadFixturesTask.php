<?php

/**
 * An extension of the build task class for loading fixtures to create data objects.
 */
class LoadFixturesTask extends BuildTask
{
    /**
     * Executes the task.
     *
     * @param SS_HTTPRequest $request
     */
    public function run($request)
    {
        // Check Enabled:
        
        if ($request->getVar('enabled')) {
            
            // Load Fixtures:
            
            SilverWare::load_fixtures();
            
        }
    }
}
