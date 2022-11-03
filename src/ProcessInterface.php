<?php

namespace AndreiFilippov\Process;
interface ProcessInterface {
    static function create(string $name, int $max = 100, int $min = 0): ProcessInterface;
    static function isProcessExist(string $name): bool;

    public function getPath(): string;
    public function delete(): bool;

    public function setAdditionalValue(string $key, $value): void;
    public function getAdditionalValue(string $key);

    public function tick(int $count): int;
    public function getPercent(): int;

    public function getMin(): int;
    public function getTick(): int;
    public function getMax(): int;
    public function isFinish(): bool;

    public function setMin(int $min): void;
    public function setMax(int $max): void;
    public function setTick(int $tick): void;
}