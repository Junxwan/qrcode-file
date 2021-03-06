<?php

namespace Junxwan;

use Symfony\Component\Finder\Finder;

class Rename
{
    /**
     * @var Finder
     */
    private $file;

    /**
     * @var string
     */
    private $path;

    /**
     * Rename constructor.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->file = $this->loadFile($path);
        $this->path = $path;
    }

    /**
     * loaded file list
     *
     * @return array
     */
    public function lists()
    {
        $list = [];

        foreach ($this->file as $file) {
            $list[] = $file->getFilename();
        }

        return $list;
    }

    /**
     * batch rename
     *
     * @param string $name
     * @param int    $start
     * @param int    $format
     *
     * @return void
     */
    public function to($name, $start = 1, $format = 2)
    {
        $index = $start;

        $this->file->sortByName();

        foreach ($this->file as $file) {
            $newName = $name . '-' . sprintf("%0" . $format . "d", $index);

            list($oldName, $newName) = $this->renameToArray($file, $newName);

            $this->renameFile($oldName, $newName);

            $index++;
        }
    }

    /**
     * rename file name
     *
     * @param string $oldName
     * @param string $newName
     *
     * @return bool
     */
    protected function renameFile($oldName, $newName)
    {
        return rename($oldName, $newName);
    }

    /**
     * load file according to path
     *
     * @param string $path
     *
     * @return Finder
     */
    private function loadFile($path)
    {
        return Finder::create()->files()->in($path);
    }

    /**
     * rename info array
     *
     * @param \Symfony\Component\Finder\SplFileInfo $file
     * @param string                                $toName
     *
     * @return array
     */
    private function renameToArray($file, $toName)
    {
        return [
            $file->getPathname(),
            $file->getPath() . '/' . $toName . '.' . $file->getExtension(),
        ];
    }
}
