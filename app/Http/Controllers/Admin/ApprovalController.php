<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ApprovalStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\RejectApprovalRequest;
use App\Models\WalletApproval;
use App\Services\WalletApprovalService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        $approvals = WalletApproval::with(['user', 'wallet', 'requester', 'approver', 'rejecter'])
            ->when($request->status, fn ($query) => $query->where('status', $request->status))
            ->latest()
            ->paginate(15);

        $statuses = ApprovalStatus::cases();

        return view('backend.approvals.index', compact('approvals', 'statuses'));
    }

    public function show(WalletApproval $approval)
    {
        $approval->load(['user', 'wallet', 'transaction', 'requester', 'approver', 'rejecter']);

        return view('backend.approvals.show', compact('approval'));
    }

    public function approve(WalletApproval $approval, WalletApprovalService $walletApprovalService)
    {
        try {
            $walletApprovalService->approve($approval, auth('admin_user')->user());

            return redirect()->route('admin.approvals.show', $approval)->with('update', 'Approval approved successfully.');
        } catch (Exception $error) {
            Log::warning('Approval approve failed.', [
                'admin_user_id' => auth('admin_user')->id(),
                'approval_id' => $approval->id,
                'exception' => $error,
            ]);

            return back()->withErrors(['fail' => $this->friendlyApprovalError($error)]);
        }
    }

    public function reject(RejectApprovalRequest $request, WalletApproval $approval, WalletApprovalService $walletApprovalService)
    {
        try {
            $walletApprovalService->reject($approval, auth('admin_user')->user(), $request->validated('reject_reason'));

            return redirect()->route('admin.approvals.show', $approval)->with('update', 'Approval rejected successfully.');
        } catch (Exception $error) {
            Log::warning('Approval reject failed.', [
                'admin_user_id' => auth('admin_user')->id(),
                'approval_id' => $approval->id,
                'exception' => $error,
            ]);

            return back()->withErrors(['fail' => $this->friendlyApprovalError($error)]);
        }
    }

    private function friendlyApprovalError(Exception $error): string
    {
        $allowedMessages = [
            'Self approval is not allowed.',
            'This approval has already been processed.',
            'You are not authorized to approve this request.',
            'The amount is greater than the wallet balance.',
        ];

        if (in_array($error->getMessage(), $allowedMessages, true)) {
            return $error->getMessage();
        }

        return 'The approval could not be processed. Please refresh the page and try again.';
    }
}
