<?php
/**
 * Created by PhpStorm.
 * User: zysxqn
 * Date: 2018/10/28
 * Time: 1:30
 */

namespace app\index\controller;

use think\Db;
use think\Session;

// Helper functions for ScheduleSwiss.
function GetMatchupsfromMatches($matches) {
  $previous_matchups = array();
  foreach ($matches as $match) {
    $matchup = [$match['TeamAID'], $match['TeamBID']];
    array_push($previous_matchups, $matchup);
  }
  return $previous_matchups;
}

// Recursive function to schedule matchups using Swiss rule.
function ScheduleSwissRecursively(&$current_standing, $previous_matches, &$current_schedule, &$result) {
  if (!empty($result)) {
    // Have found result. Return all the way to the upmost caller.
    return;
  }

  if (empty($current_standing)) {
    // Found a result, record it and return.
    $result = $current_standing;
    return;
  }

  // Pick up the opponent for the 1st team in current_standing,
  // from highest to lowest.
  for ($i = 1; $i < count($current_standing); ++$i) {
    $team1 = $current_standing[0]['ID'];
    $team2 = $current_standing[$i]['ID'];
    $matchup = [$team1, $team2];
    if (in_array($matchup, $previous_matches)) {
      // team1 and team2 have met before, try next candidate.
      continue;
    } else {
      // team1 and team2 haven't met before. Try schedule them.
      array_push($current_schedule, $matchup);
      unset($current_standing[array_search($team1, $current_standing)]);
      unset($current_standing[array_search($team2, $current_standing)]);
      // Schedule the remaining teams recursively.
      ScheduleSwissRecursively($current_standing, $previous_matches, $current_schedule, $result);
      // No possible schedule could be found after scheduling team1 vs. team 2,
      // We need to restore the lists, and try next candidate.
      unset($current_schedule[array_search($matchup, $current_schedule)]);
      array_splice($current_standing, 0, 0, $team1);
      array_splice($current_standing, $i, 0, $team2);
    }
  }
}

// Main function to schedule matchups.
function ScheduleMatchupsInternal($current_standing, $previous_matches) {
  $matchups = array();
  if (empty(previous_matches)) {
    // First round, 1st vs. 28th, 2nd vs. 27th, etc.
    $num_teams = count(current_standing);
    for ($i = 0; $i < $num_teams / 2; ++$i) {
      $matchup = [$current_standing[i]['ID'], $current_standing[$num_teams - 1 - $i]['ID']];
      array_push($matchups, $matchup);
    }
  } else {
    // Not first round. Use the recursive function to schedule.
    ScheduleSwissRecursively($current_standing, $previous_matches, array(), $matchups);
  }
  return $matchups;
}

function UnhappyTeams($current_schedule, $preferences) {
  $unhappy_teams = array();
  for ($slot = 0; $slot < count($current_schedule); ++$slot) {
    
  }
}

// Helper function to check if a team is already scheduled.
function Scheduled($team, $current_schedule) {
  foreach ($current_schedule as $matchup) {
    if (in_array($team, $matchup)) {
      return true;
    }
  }
  return false;
}

// Helper function to find the matchup of a team.
function FindMatchup($team, $matchups) {
  foreach ($matchups as $matchup) {
    if (in_array($team, $matchup)) {
      return $matchup;
    }
  }
  return array();
}

// Helper function to insert current_schedule into schedule.
// Sorted by the number of unhappy teams, from least to most.
function InsertSchedules(&$schedules, $current_schedule, $preferences) {
  $num_current_unhappy_teams = count(UnhappyTeams($current_schedule, $preferences));
  for ($i = 0; i < count($schedules); ++$i) {
    $num_unhappy_teams = count(UnhappyTeams($schedules[$i], $preferences));
    if ($num_current_unhappy_teams < $num_unhappy_teams) {
      array_slice($schedules, $i, 0, $current_schedule);
      break;
    }
  }
  array_push($schedules, $current_schedule);
}

// Helper functions for ScheduleTime.
// A recursive method to schedule time based on preferences.
function ScheduleTimeRecursively(
  $remaining_matchups, $current_schedule,
  $max_schedules, $unhappy_teams_threshold,
  &$schedules, $preferences) {
  // we should check this first so that if we have
  // found the result we should return all the way
  // to the caller immediately.
  if (count($schedules) >= $max_schedules) {
    return;
  }
  // next we check this so that we can fall back to
  // other attempts.
  if (count(UnhappyTeams($current_schedule, $preferences)) > $unhappy_teams_threshold) {
    return;
  } elseif (empty($remaining_matchups)) {
    // Found a solution. Store it and return.
    // We may continue to find other solutions.
    InsertSchedules($schedules, $current_schedule, $preferences);
    return;
  } else {
    // Try to schedule the first matchup in remaining matchups.
    $matchup = $remaining_matchups[0];
    for ($slot =0 ; $slot < count($current_schedule; ++$slot)) {
      if (!empty($current_schedule[$slot])) {
        # The slot has already been scheduled. Skip.
        continue;
      }
      # The slot is empty, schedule it.
      $current_schedule[$slot] = $matchup;
      unset($remaining_matchups[array_search($matchup, $remaining_matchups)]);
      ScheduleTimeRecursively(
        $remaining_matchups, $current_schedule,
        $max_schedules, $unhappy_teams_threshold,
        $schedules, $preferences);
      #Schedule the current slot returns. Restore the lists and try next slot.
      array_splice($remaining_matchups, $slot, 0, $matchup);
      $current_schedule[$slot] = [];
    }
  }
}

// Main function to schedule time.
function ScheduleTimeInternal(
  $matchups, $preferences, $hard_rules,
  $max_schedules, $unhappy_teams_threshold,
  &$schedules, &$error_msg) {
  // Store the current schedule. A list of 14 matchups, ranked by
  // 9:15 Cour1, 9:15 Court2, ... 15:15 Court1, 15:15 Court2
  // Note one of the slots will be empty.
  $current_schedule = [];
  for ($i = 0; $i < 14; $i++) {
    $current_schedule[$i] = [];
  }

  $remaining_matchups = $matchups;

  // This step is IMPORTANT. Otherwise, whatever teams
  // appear early in $matchups will always be scheduled
  // to the morning games.
  shuffle($remaining_matchups);

  // Fulfill Hard Rules.
  foreach ($hard_rules as $rule) {
    $team = $rule[0];
    $time = substr($rule[1], 0, 1);
    // If the team has already been scheduled, skip.
    if (Scheduled($team, $current_schedule)) {
      continue;
    }

    $matchup = FindMatchup($team, remaining_matchups);
    // find the corresponding slot.
    // Time 9 corresponds to slot 0 and 1.
    // Time 10 corresponds to slot 2 and 3. etc.
    $slot = ($time - 9) * 2;
    if (empty($current_schedule[$slot]) {
      $current_schedule[$slot] = $matchup;
      unset($remaining_matchups[
        array_search($matchup, $remaining_matchups)
      ]);
    } elseif (empty($current_schedule[$slot + 1])) {
      $current_schedule[$slot + 1] = $matchup;
      unset($remaining_matchups[
        array_search($matchup, $remaining_matchups)
      ]);
    } else {
      $error_msg = 'Hard requirements cannot be satisfied. Please specify again';
      return false;
    }
  }

  // Fulfill preferences.
  ScheduleTimeRecursively(
    $remaining_matchups, $current_schedule,
    $max_schedules, $unhappy_teams_threshold,
    $schedules, $preferences);
  return true;
}

class Schedule extends Base {
  private $MAX_SCHEDULES = 1;
  private $UNHAPPY_TEAMS_THRESHOLD = 4;

  public function ScheduleMatchups() {
    // Read teams and matches info.
    $database_teams = Db::name('bb_team')
        ->where('SeasonID', $seasonid)
        ->select();

    $matches = Db::name('bb_match')
        ->where('SeasonID', $seasonid)
        ->where('State', 1)
        ->select();

    // Get Current Standings.
    $current_standing = GetRankingFromMatches($matches, $database_teams);

    // Get Matchups history.
    $previous_matches = GetMatchupsfromMatches($matches);

    // Calculate Matchups.
    $matchups = ScheduleMatchupsInternal($current_standing, $previous_matches);

    return $matchups;
  }

  public function ScheduleTime($men_matchups, $date, $hard_rules) {
    // Read Women Matchups according to date.
    $women_matchups =
    $matchups = $men_matchups + $women_matchups

    // Read team preferences
    $preferences =

    // Calculate Schedules.
    $schedules = ScheduleTimeInternal($matchups, $preferences, $hard_rules, $MAX_SCHEDULES, $UNHAPPY_TEAMS_THRESHOLD);

    return $schedules;
  }
}
