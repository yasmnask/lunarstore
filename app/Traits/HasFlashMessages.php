<?php

namespace App\Traits;

trait HasFlashMessages
{
  public string $flashMessage = '';
  public string $flashType = '';
  public bool $showFlash = false;
  public bool $autoHide = false;
  public int $autoHideSeconds = 0;

  /**
   * Show a flash message
   */
  public function showFlash(string $message, string $type = 'error'): void
  {
    $this->flashMessage = $message;
    $this->flashType = $type;
    $this->showFlash = true;
    $this->autoHide = false;
    $this->autoHideSeconds = 0;
  }

  /**
   * Show an error flash message
   */
  public function flashError(string $message): void
  {
    $this->showFlash($message, 'error');
  }

  /**
   * Show a success flash message
   */
  public function flashSuccess(string $message): void
  {
    $this->showFlash($message, 'success');
  }

  /**
   * Show an info flash message
   */
  public function flashInfo(string $message): void
  {
    $this->showFlash($message, 'info');
  }

  /**
   * Show a warning flash message
   */
  public function flashWarning(string $message): void
  {
    $this->showFlash($message, 'warning');
  }

  /**
   * Clear the flash message
   */
  public function clearFlash(): void
  {
    $this->flashMessage = '';
    $this->flashType = '';
    $this->showFlash = false;
    $this->autoHide = false;
    $this->autoHideSeconds = 0;
  }

  /**
   * Close the flash message (alias for clearFlash)
   */
  public function closeFlash(): void
  {
    $this->clearFlash();
  }

  /**
   * Auto-dismiss flash message after specified seconds
   */
  public function flashWithTimeout(string $message, string $type = 'success', int $seconds = 5): void
  {
    $this->flashMessage = $message;
    $this->flashType = $type;
    $this->showFlash = true;
    $this->autoHide = true;
    $this->autoHideSeconds = $seconds;
  }
}
