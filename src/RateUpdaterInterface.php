<?php

namespace Drupal\currency_converter;

/**
 * Orchestrates fetching and storing exchange rates.
 */
interface RateUpdaterInterface {

  /**
   * Fetches and stores fresh rates only if the update interval has elapsed.
   *
   * This is what hook_cron() calls, so the refresh cadence is independent of
   * how often Drupal cron actually runs.
   */
  public function updateIfDue(): void;

  /**
   * Fetches and stores fresh rates immediately, bypassing the timer.
   *
   * @return bool
   *   TRUE if rates were fetched and stored, FALSE if the update failed (API
   *   error) or was skipped because another update was already in progress.
   */
  public function updateNow(): bool;

}
