<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GitHubTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_github_requires_a_token(){

        $this->actingAs(factory('App\User')->create());

        $user = factory('App\User')->make(['github_token' => null]);

        $response = $this->post('/save-github-token',$user->toArray());

        //We should expect a 404 error
        $response->assertStatus(404);
    }

    /** @test */
    public function a_github_update_a_token(){

        $this->actingAs(factory('App\User')->create());

        $user = factory('App\User')->make(['github_token' => 'testToken1234545']);

        $response = $this->post('/save-github-token',$user->toArray());

        //We should expect a 404 error
        $response->assertStatus(200);
    }

    /** @test */
    public function a_github_starred_repo_required_github_token(){

        $this->actingAs(factory('App\User')->create());

        $user = factory('App\User')->make(['github_token' => '']);

        $this->post('/save-github-token',$user->toArray());

        $response = $this->get('/get-github-starred-repo');

        //We should expect a 404 error
        $response->assertStatus(404);
    }
}
