<?php

namespace App\Exceptions;

use Exception;

class BackupRestoreException extends Exception
{
    /**
     * The safety backup filename (if created)
     */
    protected ?string $safetyBackup = null;

    /**
     * Set the safety backup filename
     *
     * @param string|null $filename
     * @return $this
     */
    public function setSafetyBackup(?string $filename): self
    {
        $this->safetyBackup = $filename;
        return $this;
    }

    /**
     * Get the safety backup filename
     *
     * @return string|null
     */
    public function getSafetyBackup(): ?string
    {
        return $this->safetyBackup;
    }

    /**
     * Get the detailed error message with safety backup info
     *
     * @return string
     */
    public function getDetailedMessage(): string
    {
        $message = $this->getMessage();
        
        if ($this->safetyBackup) {
            $message .= " Safety backup available: {$this->safetyBackup}";
        }
        
        return $message;
    }
}
