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
   */
  public function updateNow(): void;

}
