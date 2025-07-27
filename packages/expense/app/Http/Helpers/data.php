<?php
    function prepamentDetail($clearHeader, $seq) {
        $line = $clearHeader->lines->where('seq_number', $seq)->first();
        return optional($line)->amount;
    }

    function expenseDesc($expense) {
        $coaModel = (new Packages\expense\app\Models\COAListV);
        $setName = $coaModel->getDefaultSetName();
        // dd($setName);
        $coa = explode('.', $expense);
        $concatCoa = '';

        $segment1 = $coaModel->LOVDesc($setName->segment1, $coa[0]);
        $segment2 = $coaModel->LOVDesc($setName->segment2, $coa[1]);
        $segment3 = $coaModel->LOVDesc($setName->segment3, $coa[2]);
        $segment4 = $coaModel->LOVDesc($setName->segment4, $coa[3]);
        $segment5 = $coaModel->LOVDesc($setName->segment5, $coa[4]);
        $segment6 = $coaModel->LOVDesc($setName->segment6, $coa[5]);
        $segment7 = $coaModel->LOVDesc($setName->segment7, $coa[6]);
        $segment8 = $coaModel->LOVDesc($setName->segment8, $coa[7]);
        $segment9 = $coaModel->LOVDesc($setName->segment9, $coa[8]);
        $segment10 = $coaModel->LOVDesc($setName->segment10, $coa[9]);
        $segment11 = $coaModel->LOVDesc($setName->segment11, $coa[10]);
        $segment12 = $coaModel->LOVDesc($setName->segment12, $coa[11]);
        $segment13 = $coaModel->LOVDesc($setName->segment13, $coa[12]);

        $concatCoa = $segment4->description.'.'.$segment5->description.'.'.$segment6->description.'.'.$segment7->description.'.'.$segment8->description.'.'.$segment9->description.'.'.$segment10->description.'.'.$segment11->description;
        return $concatCoa;
    }

    function thaiMonth($value, $full = true) {
        $months = [
            '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน',
            '05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฎาคม', '08' => 'สิงหาคม',
            '09' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม',
        ];
        return data_get($months, $value, '');
    }

    function thaiNumberToWordsFull($number) {
        $digit = ['', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า'];
        $position = ['', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน'];

        $result = '';
        $number = ltrim($number, '0');

        if ($number === '') return 'ศูนย์';

        $chunks = [];
        while (strlen($number) > 0) {
            $chunks[] = substr($number, -6);
            $number = substr($number, 0, -6);
        }

        $chunks = array_reverse($chunks);
        $millionText = '';

        foreach ($chunks as $i => $chunk) {
            $chunk = str_pad($chunk, 6, '0', STR_PAD_LEFT);
            $chunkText = '';
            for ($j = 0; $j < 6; $j++) {
                $n = (int)$chunk[$j];
                if ($n == 0) continue;

                $pos = 5 - $j;

                if ($pos == 1 && $n == 2) {
                    $chunkText .= 'ยี่' . $position[$pos];
                } elseif ($pos == 1 && $n == 1) {
                    $chunkText .= 'สิบ';
                } elseif ($pos == 0 && $n == 1 && $chunkText !== '') {
                    $chunkText .= 'เอ็ด';
                } else {
                    $chunkText .= $digit[$n] . $position[$pos];
                }
            }

            if ($chunkText !== '') {
                if ($i < count($chunks) - 1) {
                    $millionText .= $chunkText . 'ล้าน';
                } else {
                    $millionText .= $chunkText;
                }
            } elseif ($i < count($chunks) - 1) {
                $millionText .= 'ศูนย์ล้าน';
            }
        }

        return $millionText;
    }

    function thaiDigitsToWords($digits) {
        $digit = ['ศูนย์', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า'];
        $words = '';
        $len = strlen($digits);
        for ($i = 0; $i < $len; $i++) {
            $n = (int)$digits[$i];
            $words .= $digit[$n] ;
        }
        return trim($words);
    }

    function numberToTextConverter($amount)
    {
        $amount_number = number_format($amount, 2, ".", "");
        $pt = strpos($amount_number, ".");
        $number = $fraction = "";

        if ($pt === false) {
            $number = $amount_number;
        } else {
            $number = substr($amount_number, 0, $pt);
            $fraction = substr($amount_number, $pt + 1);
        }

        $ret = "";
        $baht = readNumber($number);
        if ($baht != "") {
            $ret .= $baht . "บาท";
        }

        $satang = readNumber($fraction);
        if ($satang != "") {
            $ret .=  $satang . "สตางค์";
        } else {
            $ret .= "ถ้วน";
        }
        return $ret;
    }

    function readNumber($number)
    {
        $position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
        $number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
        $number = $number + 0;
        $ret = "";

        if ($number == 0) {
            return $ret;
        }
        if ($number > 1000000) {
            $ret .= readNumber(intval($number / 1000000)) . "ล้าน";
            $number = intval(fmod($number, 1000000));
        }

        $divider = 100000;
        $pos = 0;
        while ($number > 0) {
            $d = intval($number / $divider);
            $ret .= (($divider == 10) && ($d == 2)) ? "ยี่" :
              ((($divider == 10) && ($d == 1)) ? "" :
              ((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));
            $ret .= ($d ? $position_call[$pos] : "");
            $number = $number % $divider;
            $divider = $divider / 10;
            $pos++;
        }
        return $ret;
    }
