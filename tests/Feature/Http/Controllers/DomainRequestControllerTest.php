<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\DomainRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\DomainRequestController
 */
class DomainRequestControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    /**
     * @test
     */
    public function index_behaves_as_expected()
    {
        $domainRequests = DomainRequest::factory()->count(3)->create();

        $response = $this->get(route('domain-request.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function store_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\DomainRequestController::class,
            'store',
            \App\Http\Requests\DomainRequestStoreRequest::class
        );
    }

    /**
     * @test
     */
    public function store_saves()
    {
        $tenant_id = $this->faker->word;
        $requested_domain = $this->faker->word;
        $status = $this->faker->numberBetween(-10000, 10000);

        $response = $this->post(route('domain-request.store'), [
            'tenant_id' => $tenant_id,
            'requested_domain' => $requested_domain,
            'status' => $status,
        ]);

        $domainRequests = DomainRequest::query()
            ->where('tenant_id', $tenant_id)
            ->where('requested_domain', $requested_domain)
            ->where('status', $status)
            ->get();
        $this->assertCount(1, $domainRequests);
        $domainRequest = $domainRequests->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function show_behaves_as_expected()
    {
        $domainRequest = DomainRequest::factory()->create();

        $response = $this->get(route('domain-request.show', $domainRequest));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    /**
     * @test
     */
    public function update_uses_form_request_validation()
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\DomainRequestController::class,
            'update',
            \App\Http\Requests\DomainRequestUpdateRequest::class
        );
    }

    /**
     * @test
     */
    public function update_behaves_as_expected()
    {
        $domainRequest = DomainRequest::factory()->create();
        $tenant_id = $this->faker->word;
        $requested_domain = $this->faker->word;
        $status = $this->faker->numberBetween(-10000, 10000);

        $response = $this->put(route('domain-request.update', $domainRequest), [
            'tenant_id' => $tenant_id,
            'requested_domain' => $requested_domain,
            'status' => $status,
        ]);

        $domainRequest->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($tenant_id, $domainRequest->tenant_id);
        $this->assertEquals($requested_domain, $domainRequest->requested_domain);
        $this->assertEquals($status, $domainRequest->status);
    }


    /**
     * @test
     */
    public function destroy_deletes_and_responds_with()
    {
        $domainRequest = DomainRequest::factory()->create();

        $response = $this->delete(route('domain-request.destroy', $domainRequest));

        $response->assertNoContent();

        $this->assertModelMissing($domainRequest);
    }
}
