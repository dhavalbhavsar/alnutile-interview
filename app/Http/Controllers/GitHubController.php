<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

class GitHubController extends Controller
{
    /**
     * Get Github Token.
     *
     * @return \Illuminate\Http\Response
     */
    public function getToken()
    {
        $tokenDisplay = 'Please <a href="https://docs.github.com/en/github/authenticating-to-github/keeping-your-account-and-data-secure/creating-a-personal-access-token">click here to generate GitHub Token</a>.';
        $tokenFalg = false;
        
        if(!empty(auth()->user()->github_token)){
            $tokenDisplay = decrypt(auth()->user()->github_token);
            $tokenFalg = true;
        }

        return response()->json([
            'message'=>'Token fetched Successfully!!',
            'github_token_display'=> $tokenDisplay,
            'github_token_flag' => $tokenFalg,
        ]);
    }

    /**
     * Save Github Token.
     *
     * @return \Illuminate\Http\Response
     */
    public function saveToken(Request $request)
    {
        if(empty($request->github_token)){
            return response()->json([
                'message'=>'Please enter GitHub Token.'
            ],404);
        }

        $user = User::find(auth()->user()->id);
        $user->github_token = encrypt($request->github_token);
        $user->save();

        return response()->json([
            'message'=>'Token Save Successfully!!'
        ]);
    }

    /**
     * Get Starred Repo.
     *
     * @return \Illuminate\Http\Response
     */
    public function getGithubStarredRepo(){

        try {

            if(empty(auth()->user()->github_token)){
                return response()->json([
                    'message'=>'Please enter GitHub Token.'
                ],404);
            }

            $client = new \Github\Client();
            $client->authenticate(decrypt(auth()->user()->github_token), null, \Github\Client::AUTH_ACCESS_TOKEN);
            $allRepos = $client->api('current_user')->repositories();

            $finalStarredRepos = [];

            foreach ($allRepos as $repo) {
                if($repo['stargazers_count'] > 0){
                    $finalStarredRepos[] = $repo;
                }
            }

            return response()->json([
                'message'=>'GitHub Starred Repositories Listed Successfully!!',
                'starred_repo'=> $finalStarredRepos
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'message'=> $e->getMessage()
            ],404);
        }        

    }
}
