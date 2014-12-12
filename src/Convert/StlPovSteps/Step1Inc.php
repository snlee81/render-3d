<?php

namespace Libre3d\Render3d\Convert\StlPovSteps;

use Libre3d\Render3d\Render3d,
	Libre3d\Render3d\Convert\ConvertAbstract;

class Step1Inc extends ConvertAbstract {
	/**
	 * TODO: refactor
	 * @var boolean
	 */
	protected $silent = false;

	public function convert($singleStep) {
		if ($this->Render3d->fileType() !== 'stl') {
			// TODO: Throw exception?
			return;
		}

		// TODO: Figure out how to handle "silent"
		if ($this->silent) {
			//make sure to supress output...
			ob_start();
		}
		
		$stl2pov = $this->Render3d->executable('stl2pov');
		$file = $this->Render3d->file();
		
		$currentDir = getcwd();
		
		//we need to be in base directory for all the rendering stuff to work...
		chdir($this->Render3d->workingDir());
		
		// NOTE: older version syntax.
		$cmd = "{$stl2pov} -s \"{$file}.stl\" > \"{$file}.pov-inc\"";
		
		$this->Render3d->cmd($cmd);
		//go back to normal folder
		chdir ($currentDir);

		$filename = $this->Render3d->workingDir().$file.'.pov-inc';
		
		if (!file_exists($filename)) {
			ob_end_clean();
			return $this->error("Error creating INC file!  Cannot proceed.<br />$cmd");
		}
		$inc_contents = file_get_contents($filename);
		if (!strlen($inc_contents)) {
			ob_end_clean();
			return $this->error("Contents of INC file are empty...");
		}
		$this->Render3d->fileType('pov-inc');
		return true;
	}

	public function error($msg) {
		echo $msg;
	}
}