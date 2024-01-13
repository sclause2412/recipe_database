<?php

namespace App\Actions\Livewire;

trait CleanupInput
{
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function cleanInput($data)
    {
        if (!is_array($data)) {
            return $this->null($this->trim($data));
        }

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->cleanInput($value);
            } else {
                $data[$key] = $this->null($this->trim($value, $key));
            }
        }

        return $data;
    }


    /* Trim Strings */
    private function trim($value, $key = null)
    {
        if (in_array($key, $this->except, true) || !is_string($value)) {
            return $value;
        }

        return preg_replace('~^[\s\x{FEFF}\x{200B}]+|[\s\x{FEFF}\x{200B}]+$~u', '', $value) ?? trim($value);
    }


    private function null($value, $key = null)
    {
        return $value === '' ? null : $value;
    }
}
