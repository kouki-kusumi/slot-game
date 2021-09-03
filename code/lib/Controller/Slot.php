<?php

namespace Controller;

class Slot extends \Controller
{
    private $coin, $bet, $message;
    private $slot = [];

    public function __construct($coin, $bet)
    {
        $this->coin = $coin;
        $this->bet = $bet;
    }

    public function run()
    {
        if ($this->bet > $this->coin) {
            // コインより多いBET
            $this->message = $this->bet . '枚のコインは持っていません';
        } elseif (ctype_digit($this->bet) && substr($this->bet, 0, 1) !== '0') {
            // スロット開始
            $this->coin -= $this->bet;
            $this->playWithSlot();
            $r = $this->hitCheck();
            $getCoin = round($this->bet * $r);
            $this->coin += $getCoin;

            if ($r > 0) {
                $this->message = "当たり! " . $getCoin  . '枚のコインが返金されました';
            }

            if ($this->coin <= \Constant\SlotConst::GAMEOVER_COIN) {
                $this->message = 'GAME OVER';
                $this->coin = \Constant\SlotConst::GAMEOVER_COIN;
            } elseif ($this->coin >= \Constant\SlotConst::GAMECLEAR_COIN) {
                $this->message = 'GAME CLEAR';
                $this->coin = \Constant\SlotConst::GAMECLEAR_COIN;
            }

            if (!isset($this->message)) {
                $this->message = 'BETしてください';
            }
        } else {
            $this->message = '有効な値を入力してください';
        }
    }

    private function playWithSlot()
    {
        for ($i = 0; $i < \Constant\SlotConst::LINE; $i++) {
            for ($j = 0; $j < \Constant\SlotConst::LINE; $j++) {
                $row[] = random_int(\Constant\SlotConst::MIN_RANDOM_NUMBER, \Constant\SlotConst::MAX_RANDOM_NUMBER);
                // $row[] = random_int(MAX_SLOT_NUMBER, MAX_SLOT_NUMBER);
            }
            $this->slot[] = $row;
            $row = null;
        }
    }

    // 揃ってるかチェックし揃った数に応じて倍率$rを返す
    private function hitCheck()
    {
        // 横列
        for ($i = 0; $i < \Constant\SlotConst::LINE; $i++) {
            for ($j = 0; $j < \Constant\SlotConst::LINE; $j++) {
                $row[] = $this->slot[$i][$j];
            }
            $line[] = $row;
            $row = null;
        }

        // 横列
        for ($i = 0; $i < \Constant\SlotConst::LINE; $i++) {
            for ($j = 0; $j < \Constant\SlotConst::LINE; $j++) {
                $row[] = $this->slot[$j][$i];
            }
            $line[] = $row;
            $row = null;
        }

        // 斜め列
        for ($i = 0; $i < \Constant\SlotConst::LINE; $i++) {
            $row[] = $this->slot[$i][$i];
        }
        $line[] = $row;
        $row = null;
        $j = \Constant\SlotConst::LINE - 1;
        for ($i = 0; $i < \Constant\SlotConst::LINE; $i++) {
            $row[] = $this->slot[$i][$j];
            $j--;
        }
        $line[] = $row;

        $hit = 0;
        foreach ($line as $value) {
            if (1 === count(array_unique($value))) {
                $hit++;
            }
        }

        $r = 0;

        if ($hit === 0) {
            $r = \Constant\SlotConst::MULTIPLIER_0;
        } elseif (1 <= $hit && $hit < 3) {
            $r = \Constant\SlotConst::MULTIPLIER_1_2;
        } elseif (3 <= $hit && $hit < 6) {
            $r = \Constant\SlotConst::MULTIPLIER_3_5;
        } elseif ($hit === 6) {
            $r = \Constant\SlotConst::MULTIPLIER_6;
        } elseif ($hit === 8) {
            $r = \Constant\SlotConst::MULTIPLIER_8;
        }

        return $r;
    }

    // getter
    public function getCoin()
    {
        return $this->coin;
    }
    public function getSlot()
    {
        return $this->slot;
    }
    public function getMessage()
    {
        return $this->message;
    }
}
