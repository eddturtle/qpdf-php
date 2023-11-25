<?php

namespace EddTurtle\Qpdf;

use mikehaertl\shellcommand\Command;

class Pdf
{

    /**
     * @var Command the command instance that executes QPDF
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

    public function resetCommand()
    {
        $this->command = new Command($this->options);
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

    /**
     * Add a PDF file path to be operated upon
     *
     * @param string $pathName              String path to pdf file
     * @return false|$this
     *
     *
     */
    public function addPage($pdfName)
    {
        if (!file_exists($pdfName)) {
            $this->error = "Added page '" . $pdfName . "' does not exist";
            return false;
        }
        $this->pages[] = $pdfName;
        return $this;
    }

    /**
     * Return array of all added file paths
     *
     * @return array
     *
     */
    public function getPages()
    {
        return $this->pages;
    }

    /**
     * Merge all added pages in the object into a single output file.
     *
     * @param string $target                Path to output file
     * @return bool
     *
     */
    public function merge($target)
    {
        $cmd = $this->getCommand();
        $cmd->addArg('--empty', null, false);
        $cmd->addArg('--pages', $this->pages);
        $cmd->addArg('--', null, false);
        $cmd->addArg( $target, null, false);
        return $this->execute();
    }

    /**
     * Split a document into a smaller number of pages.
     *
     * @param string $target                Path to output file
     * @param string $pagesPerGroup         Integer indicating how many pages per output file.  Default is 1.
     *                                      in the QPDF docs.  Null value assumes to apply to all pages.  Default is null.
     * @return bool
     *
     * @throws \Exception
     */
    public function split($target, $pagesPerGroup=1)
    {
        if(count($this->pages) > 1) {
            throw new \Exception("Error! Currently unable to split when more than one PDF file is specified.");
        }

        $cmd = $this->getCommand();
        $cmd->addArg('--split-pages=', $pagesPerGroup, false);
        $cmd->addArg('', $this->pages, false);
        $cmd->addArg('--', null, false);
        $cmd->addArg( $target, null, false);
        return $this->execute();
    }

    /**
     * Rotate pages in the object.
     *
     * @param string $target                Path to output file
     * @param string $direction             String indicating rotation direction and amount. '+' for clockwise or '-'
     *                                      for counter-clockwise. Followed by '90', '180' or '270' for amount of
     *                                      rotation in degrees.  Defaults to '+90'
     * @param string|null $pageString       String to indicate which pages to rotate. See ['Page Ranges'](https://qpdf.readthedocs.io/en/stable/cli.html#page-ranges)
     *                                      in the QPDF docs.  Null value assumes to apply to all pages.  Default is null.
     * @return bool
     *
     * @throws \Exception
     */
    public function rotate($target, $direction='+90', $pageString=null)
    {
        $rotationCmd = $direction;
        if($pageString) {
            $rotationCmd .= ':'.$pageString;
        }

        if(count($this->pages) > 1) {
            throw new \Exception("Error! Currently unable to rotate when more than one PDF file is specified.");
        }

        $cmd = $this->getCommand();
        $cmd->addArg('--rotate=', $rotationCmd, false);
        $cmd->addArg('', $this->pages, false);
        $cmd->addArg('--', null, false);
        $cmd->addArg( $target, null, false);
        return $this->execute();
    }

    /**
     * Get a count of all pages in the current object (across all added documents).
     *
     * @return int Number of pages
     *
     * @throws \Exception
     */
    public function getPageCount()
    {
        $pageTotal = 0;
        foreach($this->pages as $file) {
            $cmd = $this->resetCommand();
            $cmd->addArg('--show-npages', null, false);
            $cmd->addArg('', $file, false);
            $res = $this->execute();
            if(!$res) {
                throw new \Exception("Error getting page count. " . $this->getError());
            } else {
                $pageTotal += intval($this->output);
            }
        }
        return $pageTotal;
    }


}