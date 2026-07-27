<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CooperationAgreementService;
use App\Repositories\Contracts\CooperationAgreementRepositoryInterface;
use App\Models\CooperationAgreement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Exception;

class CooperationAgreementServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_sign_spk_transitions_to_signed()
    {
        $repo = Mockery::mock(CooperationAgreementRepositoryInterface::class);
        
        $agreement = new CooperationAgreement(['id' => 1, 'status' => \App\Enums\SpkStatus::PendingSignature]);
        $repo->shouldReceive('findOrFail')->with(1)->andReturn($agreement);
        $repo->shouldReceive('update')->once()->andReturn(true);

        $this->app->instance(CooperationAgreementRepositoryInterface::class, $repo);

        $service = $this->app->make(CooperationAgreementService::class);
        $result = $service->signSpk(1);
        
        $this->assertTrue($result);
    }
}

