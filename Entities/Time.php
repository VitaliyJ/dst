<?php

namespace Entities;

use Exception;

class Time
{
    public const ERROR_INVALID_DATA = 'Invalid data received';

    private bool $dst;
    private ?int $gmtOffset;
    private ?int $dstStart;
    private ?int $dstEnd;

    /**
     * Time constructor.
     * @param bool $dst
     * @param int|null $gmtOffset
     * @param int|null $dstStart
     * @param int|null $dstEnd
     * @throws Exception
     */
    public function __construct(bool $dst, ?int $gmtOffset, ?int $dstStart, ?int $dstEnd)
    {
        if (is_int($gmtOffset) && ($gmtOffset > 50400 || $gmtOffset < -43200)) {
            throw new Exception(self::ERROR_INVALID_DATA);
        }

        $this->dst = $dst;
        $this->gmtOffset = $gmtOffset;
        $this->dstStart = $dstStart;
        $this->dstEnd = $dstEnd;
    }

    public function dst(): bool
    {
        return $this->dst;
    }

    public function gmtOffset(): ?int
    {
        return $this->gmtOffset;
    }

    public function dstStart(): ?int
    {
        return $this->dstStart;
    }

    public function dstEnd(): ?int
    {
        return $this->dstEnd;
    }

    public function toLocal(int $timestamp): int
    {
        $offset = $this->commonOffset($timestamp);
        return $timestamp + $offset;
    }

    public function fromLocal(int $timestamp): int
    {
        $offset = $this->commonOffset($timestamp);
        return $timestamp - $offset;
    }

    private function commonOffset(int $timestamp): int
    {
        $offset = $this->gmtOffset();

        if (
            $this->dst()
            && $timestamp > (int)$this->dstStart()
            && $timestamp < (int)$this->dstEnd()
        ) {
            $offset += 60 * 60; // 1 hour
        }

        return $offset;
    }
}