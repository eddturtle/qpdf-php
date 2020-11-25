<?php

namespace EddTurtle\Qpdf;

use mikehaertl\shellcommand\Command;

class Pdf
{

    /**
     * @var Command the command instance that executes pdftk
     */
    protected $command;

    protected $error;
    protected $output;

    protected $defaults = [
        'command' => 'qpdf',
    ];
    protected $options = [];

    protected $pages = [];

    public function __construct($file = null, $options = [])
    {
        $options += $this->defaults;
        $this->setOptions($options);

        // Build command for 1st time
        $this->getCommand();
    }

    public function getCommand()
    {
        if ($this->command === null) {
            $this->command = new Command($this->options);
        }
        return $this->command;
    }

    public function execute()
    {
        $command = $this->getCommand();
        if ($command->getExecuted()) {
            return false;
        }
        if (!$command->execute()) {
            $this->error = $command->getError();
        }
        $this->output = $command->getOutput();
        return true;
    }

    public function getError()
    {
        return $this->error;
    }

    protected function setOptions($options)
    {
        if (!empty($options['command']) && file_exists($options['command'])) {
            $options['command'] = realpath($options['command']);
        }
        $this->options = $options;
    }

    protected function getOptions()
    {
        return $this->options;
    }

    public function getVersion()
    {
        $this->getCommand()
            ->addArg('--version');
        if ($this->execute()) {
            return $this->output;
        }
        return false;
    }

    public function addPage($pdfName)
    {
        if (!file_exists($pdfName)) {
            $this->error = "Added page '" . $pdfName . "' does not exist";
            return false;
        }
        $this->pages[] = $pdfName;
        return $this;
    }

    public function getPages()
    {
        return $this->pages;
    }

    public function merge($target)
    {
        $cmd = $this->getCommand();
        $cmd->addArg('--empty', null, false);
        $cmd->addArg('--pages', $this->pages);
        $cmd->addArg('--', null, false);
        $cmd->addArg( $target, null, false);
        return $this->execute();
    }

}