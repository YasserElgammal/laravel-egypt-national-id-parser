<?php

namespace YasserElgammal\LaravelEgyptNationalIdParser\Tests\Unit;

use YasserElgammal\LaravelEgyptNationalIdParser\Facades\NationalId;
use YasserElgammal\LaravelEgyptNationalIdParser\Services\NationalIdValidator;
use YasserElgammal\LaravelEgyptNationalIdParser\Tests\TestCase;
use YasserElgammal\LaravelEgyptNationalIdParser\Exceptions\InvalidNationalIdException;
use Illuminate\Support\Facades\Validator;
use YasserElgammal\LaravelEgyptNationalIdParser\Rules\EgyptianNationalId;

class NationalIdValidatorTest extends TestCase
{
    private string $validId = '29001010112341';

    public function test_it_validates_correct_id()
    {
        $result = NationalId::validate($this->validId);
        $this->assertTrue($result['status']);
        $this->assertEmpty($result['errors']);
        $this->assertIsArray($result['data']);
        $this->assertEquals('1990-01-01', $result['data']['birth_date']);
        $this->assertEquals('female', $result['data']['gender']['code']);
        $this->assertEquals('01', $result['data']['governorate']['code']);
    }

    public function test_it_fails_on_invalid_length()
    {
        $result = NationalId::validate('123');
        $this->assertFalse($result['status']);
        $this->assertContains('ID must be exactly 14 digits', $result['errors']);
    }

    public function test_it_fails_on_invalid_check_digit()
    {
        // Change check digit from 1 to 2
        $result = NationalId::validate('29001010112342');
        $this->assertFalse($result['status']);
    }

    public function test_it_parses_to_dto()
    {
        $data = NationalId::parse($this->validId);
        $this->assertNotNull($data);
        $this->assertEquals('1990-01-01', $data->getBirthDate()->format('Y-m-d'));
        $this->assertEquals('01', $data->getGovernorateCode());
    }

    public function test_it_throws_exception_on_parse_or_fail()
    {
        $this->expectException(InvalidNationalIdException::class);
        NationalId::parseOrFail('12345678901234');
    }

    public function test_laravel_validation_rule()
    {
        $rule = new EgyptianNationalId();
        
        $validator = Validator::make(['id' => $this->validId], [
            'id' => ['required', $rule]
        ]);
        $this->assertTrue($validator->passes());

        $validator = Validator::make(['id' => '12345678901234'], [
            'id' => ['required', $rule]
        ]);
        $this->assertFalse($validator->passes());
    }
}
