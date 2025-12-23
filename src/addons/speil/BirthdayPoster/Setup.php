<?php

namespace speil\BirthdayPoster;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

    // Install:
    /*
    public function installStep1()
    {
        // abc
    }
    */

    // Deinstall
    /*
    public function uninstallStep1()
    {
        // abc
    }
    */
}
