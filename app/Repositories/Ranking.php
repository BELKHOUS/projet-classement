<?php

namespace App\Repositories;

class Ranking
{
    function goalDifference(int $goalFor , int $goalAgainst):int
    {
        return $goalFor - $goalAgainst;
    }
//-------------------------------------------------------------------------------------------------
    function points(int $wonMatchCount, int $drawMatchCount): int
    {
        return $wonMatchCount*3 + $drawMatchCount;
    }
//-------------------------------------------------------------------------------------------------

    function teamWinsMatch(int $teamId, array $match): bool
    {
        if($match['team0']==$teamId) {
            if($match['score0']>$match['score1'])
            return true;
        }
        else if ($match['team1']==$teamId){
            if($match['score0']<$match['score1'])
            return true;
        }

        return false;
    }
//-------------------------------------------------------------------------------------------------
    function teamLosesMatch(int $teamId, array $match): bool
    {
        $reponse = ($match['team0']==$teamId) ? (($match['score0']<$match['score1'])) : (($match['score1']<$match['score0']) ? true : false);
        return $reponse;
    }
//-------------------------------------------------------------------------------------------------
    function teamMakesADraw(int $teamId, array $match): bool
    {
        $reponse = ($match['score0']==$match['score1'] && ($match['team0']==$teamId || $match['team1']==$teamId)) ? true : false;
        return $reponse;
    }
//-------------------------------------------------------------------------------------------------
    function goalForCountDuringAMatch(int $teamId, array $match): int
    {
    //  exprCond ? exprTrue : exprFalse

        $reponse = ($match['team0']==$teamId) ? ($match['score0']) : (($match['team1']==$teamId) ? $match['score1'] : 0);
    
        return $reponse;
    }
    function goalAgainstCountDuringAMatch(int $teamId, array $match): int
    {
        $reponse = ($match['team0']==$teamId) ? ($match['score1']) : (($match['team1']==$teamId) ? $match['score0'] : 0);
    
        return $reponse;
    }
//-------------------------------------------------------------------------------------------------
    function goalForCount(int $teamId, array $matches): int
    {
        $nbrBut0 = 0;
        $nbrBut1 = 0;
        foreach ($matches as $value)
        {
            if($value['team0'] == $teamId)
            {
                $nbrBut0 = $nbrBut0 +  $value['score0'];
            }
            if($value['team1'] == $teamId)
            {
                $nbrBut1 = $nbrBut1 +  $value['score1'];
            }
        }
        return $nbrBut0 + $nbrBut1;
    }
//-------------------------------------------------------------------------------------------------
    function goalAgainstCount(int $teamId, array $matches): int
    {
        $nbrBut0 = 0;
        $nbrBut1 = 0;
        foreach ($matches as $value)
        {
            if($value['team0'] == $teamId)
            {
                $nbrBut0 = $nbrBut0 +  $value['score1'];
            }
            if($value['team1'] == $teamId)
            {
                $nbrBut1 = $nbrBut1 +  $value['score0'];
            }
        }
        return $nbrBut0 + $nbrBut1;
    }
//-------------------------------------------------------------------------------------------------
    function wonMatchCount(int $teamId, array $matches): int
    {
        $count = 0;
        foreach($matches as $value)
        {
            if($this->teamWinsMatch($teamId, $value) == true && ($teamId == $value['team1']|| $teamId == $value['team0']))
            {
                $count = $count+1;
            }
        }

        return $count;
    }
//-------------------------------------------------------------------------------------------------
    function lostMatchCount(int $teamId, array $matches): int
    {
        $count = 0;
        foreach($matches as $value)
        {
            if($this->teamLosesMatch($teamId, $value) == true && ($teamId == $value['team1']|| $teamId == $value['team0']))
            {
                $count = $count+1;
            }
        }

        return $count;
    }
//-------------------------------------------------------------------------------------------------
    function drawMatchCount(int $teamId, array $matches): int
    {
        $count = 0;
        foreach($matches as $value)
        {
            if($this->teamMakesADraw($teamId, $value) == true && ($teamId == $value['team1'] || $teamId == $value['team0']))
            {
                $count = $count+1;
            }
        }
        return $count;
    }
//-------------------------------------------------------------------------------------------------
    function rankingRow(int $teamId, array $matches): array
    {   
        $matchPlayedCount = $this->wonMatchCount($teamId,$matches) + $this->lostMatchCount($teamId,$matches) + $this->drawMatchCount($teamId,$matches);
        $wonMatchCount = $this->wonMatchCount($teamId,$matches);
        $lostMatchCount = $this->lostMatchCount($teamId,$matches);
        $drawMatchCount = $this->drawMatchCount($teamId,$matches);
        $goalForCount=$this->goalForCount($teamId,$matches);
        $goalAgainstCount=$this->goalAgainstCount($teamId,$matches);
        $goalDifference =$this->goalDifference($goalForCount , $goalAgainstCount);
        $points = $this->points($wonMatchCount,$drawMatchCount);
        $tab = [
            'team_id'=> $teamId,'match_played_count' => $matchPlayedCount,'won_match_count'=> $wonMatchCount,
            'lost_match_count'=> $lostMatchCount,'draw_match_count'=> $drawMatchCount,'goal_for_count'=> $goalForCount,
            'goal_against_count' => $goalAgainstCount,'goal_difference'=> $goalDifference,'points'=> $points ];
        return $tab;
    }
    function unsortedRanking(array $teams, array $matches): array
    {
        $result = [];

        foreach($teams as $value)
        {
            $result[] = $this->rankingRow($value['id'], $matches);
        }

        return $result;
    }
//----------------------------------------------------------------------------------------------------------------------------------------------
    static function compareRankingRow(array $row1, array $row2): int
    {
        if ($row1['points'] != $row2['points']) return $row2['points'] - $row1['points'];
        if($row1['goal_difference'] != $row2['goal_difference']) return $row2['goal_difference'] - $row1['goal_difference'];
        return $row2['goal_for_count'] - $row1['goal_for_count'];
        
    }
    function sortedRanking(array $teams, array $matches): array
    {
        $result = $this->unsortedRanking($teams, $matches);
        usort($result, ['App\Repositories\Ranking', 'compareRankingRow']);
        for($i = 0 ; $i<count($teams) ; $i++)
        {
            $result[$i]['rank'] = $i+1;
        }
        return $result;
    }
}