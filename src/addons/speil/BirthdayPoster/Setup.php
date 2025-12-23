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

    // Hier könnten Installations-Schritte stehen, z.B.:
    /*
    public function installStep1()
    {
        // Beispiel: Eine Tabelle anlegen, falls nötig
    }
    */

    // Hier könnten Deinstallations-Schritte stehen
    /*
    public function uninstallStep1()
    {
        // Beispiel: Eigene Tabellen wieder löschen
    }
    */
}
