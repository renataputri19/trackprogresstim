<?php

namespace Tests\Feature;

use Tests\TestCase;

class VhtsRoomCalculationTest extends TestCase
{
    /**
     * Test that the VhtsControllerv3 properly calculates room usage to prevent GPR = 0
     */
    public function test_room_calculation_prevents_zero_gpr()
    {
        // Test data that would typically result in GPR = 0 due to missing room calculations
        $testData = "1\t50\t100\t10\t0\t0\t5\t10\t3\t8\t2\t6
2\t50\t100\t11\t0\t0\t6\t16\t4\t7\t3\t5
3\t50\t100\t12\t0\t0\t7\t18\t2\t9\t4\t8
4\t50\t100\t13\t0\t0\t8\t19\t5\t6\t3\t7
5\t50\t100\t14\t0\t0\t9\t20\t3\t8\t5\t9
6\t50\t100\t15\t0\t0\t10\t21\t4\t7\t6\t8
7\t50\t100\t16\t0\t0\t11\t22\t2\t9\t4\t7
8\t50\t100\t17\t0\t0\t12\t23\t5\t8\t7\t9
9\t50\t100\t18\t0\t0\t13\t24\t3\t6\t5\t8
10\t50\t100\t19\t0\t0\t14\t25\t4\t9\t6\t7";

        $response = $this->postJson('/vhtsv3/validate', [
            'data' => $testData,
            'month' => 3,
            'year' => 2025,
            'reference_tpk' => 50
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $responseData = $response->json();
        
        // Verify that GPR is not 0 and is within acceptable range
        $this->assertArrayHasKey('metrics', $responseData);
        $metrics = $responseData['metrics'];
        
        $this->assertArrayHasKey('gpr', $metrics);
        $this->assertGreaterThan(0, $metrics['gpr'], 'GPR should not be 0');
        $this->assertGreaterThanOrEqual(1.0, $metrics['gpr'], 'GPR should be at least 1.0');
        $this->assertLessThanOrEqual(3.0, $metrics['gpr'], 'GPR should be at most 3.0');

        // Verify that room calculations have been enhanced
        $this->assertArrayHasKey('data', $responseData);
        $validatedData = $responseData['data'];

        // Check that room check-ins and check-outs are no longer all zeros
        $totalRoomCheckIns = array_sum(array_column($validatedData, 'kamar_baru_dimasuki'));
        $totalRoomCheckOuts = array_sum(array_column($validatedData, 'kamar_ditinggalkan'));

        $this->assertGreaterThan(0, $totalRoomCheckIns, 'Total room check-ins should be greater than 0');
        $this->assertGreaterThan(0, $totalRoomCheckOuts, 'Total room check-outs should be greater than 0');

        // Verify room-guest relationship (approximately 2 guests per room)
        foreach ($validatedData as $day) {
            $totalGuests = $day['tamu_kemarin_asing'] + $day['tamu_kemarin_indonesia'];
            $roomsUsed = $day['kamar_digunakan_kemarin'];
            
            if ($totalGuests > 0 && $roomsUsed > 0) {
                $dailyGpr = $totalGuests / $roomsUsed;
                $this->assertGreaterThanOrEqual(0.5, $dailyGpr, "Daily GPR should be reasonable for date {$day['tanggal']}");
                $this->assertLessThanOrEqual(4.0, $dailyGpr, "Daily GPR should not be excessive for date {$day['tanggal']}");
            }
        }
    }

    /**
     * Test that room calculations are proportional to guest movements
     */
    public function test_room_guest_proportionality()
    {
        // Test data with clear guest movements
        $testData = "1\t50\t100\t10\t0\t0\t10\t20\t6\t12\t4\t8
2\t50\t100\t15\t0\t0\t12\t24\t8\t16\t6\t12";

        $response = $this->postJson('/vhtsv3/validate', [
            'data' => $testData,
            'month' => 3,
            'year' => 2025,
            'reference_tpk' => 50
        ]);

        $response->assertStatus(200);
        $validatedData = $response->json('data');

        foreach ($validatedData as $day) {
            $guestsIn = $day['tamu_baru_datang_asing'] + $day['tamu_baru_datang_indonesia'];
            $guestsOut = $day['tamu_berangkat_asing'] + $day['tamu_berangkat_indonesia'];
            $roomsIn = $day['kamar_baru_dimasuki'];
            $roomsOut = $day['kamar_ditinggalkan'];

            // When guests check in, rooms should be allocated (approximately 2 guests per room)
            if ($guestsIn > 0) {
                $expectedRoomsIn = max(1, ceil($guestsIn / 2));
                $this->assertGreaterThanOrEqual($expectedRoomsIn - 1, $roomsIn, 
                    "Room check-ins should be proportional to guest arrivals for date {$day['tanggal']}");
                $this->assertLessThanOrEqual($expectedRoomsIn + 1, $roomsIn, 
                    "Room check-ins should not be excessive for date {$day['tanggal']}");
            }

            // When guests check out, rooms should be freed (approximately 2 guests per room)
            if ($guestsOut > 0) {
                $expectedRoomsOut = max(1, ceil($guestsOut / 2));
                $this->assertGreaterThanOrEqual($expectedRoomsOut - 1, $roomsOut, 
                    "Room check-outs should be proportional to guest departures for date {$day['tanggal']}");
                $this->assertLessThanOrEqual($expectedRoomsOut + 1, $roomsOut, 
                    "Room check-outs should not be excessive for date {$day['tanggal']}");
            }
        }
    }

    /**
     * Test that the fix handles edge cases properly
     */
    public function test_edge_cases()
    {
        // Test with minimal data
        $testData = "1\t10\t20\t1\t0\t0\t1\t1\t1\t1\t0\t0";

        $response = $this->postJson('/vhtsv3/validate', [
            'data' => $testData,
            'month' => 3,
            'year' => 2025
        ]);

        $response->assertStatus(200);
        $metrics = $response->json('metrics');

        // Even with minimal data, GPR should not be 0
        $this->assertGreaterThan(0, $metrics['gpr'], 'GPR should not be 0 even with minimal data');
    }

    /**
     * Test that TPK calculation is fixed and not returning 0
     */
    public function test_tpk_calculation_fix()
    {
        // Test data that would cause TPK = 0 with the old calculation
        $testData = "1\t100\t200\t5\t0\t0\t3\t7\t2\t4\t1\t3
2\t100\t200\t6\t0\t0\t4\t10\t3\t5\t2\t4
3\t100\t200\t7\t0\t0\t5\t12\t1\t6\t3\t5";

        $response = $this->postJson('/vhtsv3/validate', [
            'data' => $testData,
            'month' => 3,
            'year' => 2025,
            'reference_tpk' => 10
        ]);

        $response->assertStatus(200);
        $metrics = $response->json('metrics');

        // Verify TPK is calculated correctly and not 0
        $this->assertArrayHasKey('tpk', $metrics);
        $this->assertGreaterThan(0, $metrics['tpk'], 'TPK should not be 0');
        $this->assertIsFloat($metrics['tpk'], 'TPK should be a float value');

        // TPK should be reasonable (between 1% and 100%)
        $this->assertGreaterThanOrEqual(1.0, $metrics['tpk'], 'TPK should be at least 1%');
        $this->assertLessThanOrEqual(100.0, $metrics['tpk'], 'TPK should not exceed 100%');
    }

    /**
     * Test that core calculation equations are preserved
     */
    public function test_core_equations_preserved()
    {
        // Test data to verify core equations work correctly
        $testData = "1\t50\t100\t10\t5\t3\t8\t12\t4\t6\t2\t4
2\t50\t100\t12\t3\t2\t10\t14\t3\t5\t3\t6";

        $response = $this->postJson('/vhtsv3/validate', [
            'data' => $testData,
            'month' => 3,
            'year' => 2025
        ]);

        $response->assertStatus(200);
        $validatedData = $response->json('data');

        // Verify that the second row follows the core calculation equations
        $day1 = $validatedData[0];
        $day2 = $validatedData[1];

        // Core room equation: kamar_digunakan_kemarin[day2] = kamar_digunakan_kemarin[day1] + kamar_baru_dimasuki[day1] - kamar_ditinggalkan[day1]
        $expectedRooms = max(1, $day1['kamar_digunakan_kemarin'] + $day1['kamar_baru_dimasuki'] - $day1['kamar_ditinggalkan']);
        $this->assertEquals($expectedRooms, $day2['kamar_digunakan_kemarin'],
            'Core room calculation equation should be preserved');

        // Core foreign guest equation: tamu_kemarin_asing[day2] = tamu_kemarin_asing[day1] + tamu_baru_datang_asing[day1] - tamu_berangkat_asing[day1]
        $expectedForeignGuests = max(0, $day1['tamu_kemarin_asing'] + $day1['tamu_baru_datang_asing'] - $day1['tamu_berangkat_asing']);
        $this->assertEquals($expectedForeignGuests, $day2['tamu_kemarin_asing'],
            'Core foreign guest calculation equation should be preserved');

        // Core Indonesian guest equation: tamu_kemarin_indonesia[day2] = tamu_kemarin_indonesia[day1] + tamu_baru_datang_indonesia[day1] - tamu_berangkat_indonesia[day1]
        $expectedIndonesianGuests = max(0, $day1['tamu_kemarin_indonesia'] + $day1['tamu_baru_datang_indonesia'] - $day1['tamu_berangkat_indonesia']);
        $this->assertEquals($expectedIndonesianGuests, $day2['tamu_kemarin_indonesia'],
            'Core Indonesian guest calculation equation should be preserved');
    }
}
