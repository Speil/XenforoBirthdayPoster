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

	/**
	* defaults
	*/
	public function installStep1()
		{
		$options = \XF::em()->getFinder('XF:Option')->whereIds([
		'SpeilBirthdayPosterThreadID',
		'SpeilBirthdayPosterUserID',
		'SpeilBirthdayPosterMinPosts'
		])->fetch();

		foreach ($options AS $option) {
		if ($option->option_id == 'SpeilBirthdayPosterMinPosts') {
		$option->option_value = 50;
		}
		// you can define defaults here
		if ($option->option_id == 'SpeilBirthdayPosterUserID') {
		$option->option_value = 1; 
		}
		$option->save();
		}
	}

/**
* deinstall
*/
public function uninstallStep1()
{
// deinstall
}
}
