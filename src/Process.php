<?php

namespace AndreiFilippov\Process;
use AndreiFilippov\Process\Exceptions\ProcessException;

class Process implements ProcessInterface {
    const PATH = __DIR__.'/processes';

    protected $name;
    protected $min;
    protected $max;
    protected $additionalData;
    protected $tick;

    public function __construct(string $name, int $max, int $min = 0) {
        $this->name = $name;
        $this->min = $min;
        $this->max = $max;
        $this->additionalData = [];
        $this->tick = $min;

        $this->update();

        return $this;
    }

    /**
     * @throws ProcessException
     */
    static function create(string $name, int $max = 100, int $min = 0): ProcessInterface {
        if(self::isProcessExist($name)) throw new ProcessException('Process is Exist');
        return new self($name, $max, $min);
    }

    /**
     * @throws ProcessException
     */
    static function get(string $name): ProcessInterface {
        if(!self::isProcessExist($name)) throw new ProcessException('Process is Not Exist');

        return unserialize(file_get_contents(self::getProcessPath($name)));
    }

    static function isProcessExist(string $name): bool {
        return file_exists(self::getProcessPath($name));
    }

    static function getProcessPath(string $name): string {
        return self::PATH.'/'.$name.'.process';
    }

    public function getPath(): string {
        return self::getProcessPath($this->name);
    }

    public function delete(): bool {
        return unlink($this->getPath());
    }

    public function update(): void {
        file_put_contents($this->getPath(), serialize($this));
    }

    public function setAdditionalValue(string $key, $value): void {
        $this->additionalData[$key] = $value;
        $this->update();
    }

    public function getAdditionalValue(string $key) {
        return $this->additionalData[$key];
    }

    public function tick(int $count = 1): int {
        $this->tick++;
        $this->update();
        return $this->tick;
    }

    public function getPercent(): int {
        return round($this->max/$this->tick*100);
    }

    /**
     * @return int
     */
    public function getTick(): int {
        return $this->tick;
    }

    /**
     * @return int
     */
    public function getMin(): int {
        return $this->min;
    }

    /**
     * @return int
     */
    public function getMax(): int {
        return $this->max;
    }
}