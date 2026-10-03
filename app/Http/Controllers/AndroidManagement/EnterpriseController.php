<?php

namespace App\Http\Controllers\AndroidManagement;

use App\Http\Controllers\Controller;
use App\Services\AndroidManagement\EnterpriseService;
use Illuminate\Http\Request;

class EnterpriseController extends Controller
{
    public function __construct(
        private EnterpriseService $enterpriseService
    ) {
    }

    public function callback(Request $request)
    {
        $enterpriseToken = $request->query('enterpriseToken');
        $signupUrlName = $request->query('signup_url_name');

        if (! $enterpriseToken) {
            return response()->json([
                'success' => false,
                'message' => 'enterpriseToken tidak ditemukan.',
            ], 400);
        }

        if (! $signupUrlName) {
            return response()->json([
                'success' => false,
                'message' => 'signup_url_name tidak ditemukan.',
            ], 400);
        }

        $enterprise = $this->enterpriseService->createEnterprise(
            enterpriseToken: $enterpriseToken,
            signupUrlName: $signupUrlName
        );

        return response()->json([
            'success' => true,
            'message' => 'Enterprise berhasil dibuat.',
            'enterprise' => [
                'id' => $enterprise->id,
                'name' => $enterprise->name,
                'display_name' => $enterprise->display_name,
                'status' => $enterprise->status,
            ],
        ]);
    }
}
