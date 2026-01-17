<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\ContactGift;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $agencyId = app('currentAgency')->id;

        $query = Contact::where('agency_id', $agencyId)->orderBy('company_name');

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('sent_gift')) {
            $query->where('sent_gift', $request->boolean('sent_gift'));
        }

        if ($request->filled('from_date')) {
            $query->whereDate('gift_sent_date', '>=', $request->input('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('gift_sent_date', '<=', $request->input('to_date'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', '%'.$search.'%')
                    ->orWhere('contact_person', 'like', '%'.$search.'%')
                    ->orWhere('mobile', 'like', '%'.$search.'%');
            });
        }

        $contacts = $query->paginate(25)->withQueryString();

        $types = [
            'client' => 'Client',
            'vendor' => 'Vendor',
            'airline' => 'Airline',
            'other' => 'Other',
        ];

        return view('contacts.index', compact('contacts', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'max:50'],
            'company_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'sent_gift' => ['nullable', 'boolean'],
            'gift_sent_date' => ['nullable', 'date'],
            'last_gift_name' => ['nullable', 'string', 'max:255'],
            'gift_dates' => ['nullable', 'string'],
        ]);

        Contact::create([
            'agency_id' => app('currentAgency')->id,
            'type' => $validated['type'],
            'company_name' => $validated['company_name'],
            'contact_person' => $validated['contact_person'] ?? null,
            'designation' => $validated['designation'] ?? null,
            'mobile' => $validated['mobile'] ?? null,
            'sent_gift' => (bool) ($validated['sent_gift'] ?? false),
            'gift_sent_date' => $validated['gift_sent_date'] ?? null,
            'last_gift_name' => $validated['last_gift_name'] ?? null,
            'gift_dates' => $validated['gift_dates'] ?? null,
        ]);

        return redirect()->route('contacts.index')->with('success', 'Contact created successfully.');
    }

    public function update(Request $request, Contact $contact)
    {
        if ($contact->agency_id !== app('currentAgency')->id) {
            abort(404);
        }

        $validated = $request->validate([
            'type' => ['required', 'string', 'max:50'],
            'company_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'sent_gift' => ['nullable', 'boolean'],
            'gift_sent_date' => ['nullable', 'date'],
            'last_gift_name' => ['nullable', 'string', 'max:255'],
            'gift_dates' => ['nullable', 'string'],
        ]);

        $contact->update([
            'type' => $validated['type'],
            'company_name' => $validated['company_name'],
            'contact_person' => $validated['contact_person'] ?? null,
            'designation' => $validated['designation'] ?? null,
            'mobile' => $validated['mobile'] ?? null,
            'sent_gift' => (bool) ($validated['sent_gift'] ?? false),
            'gift_sent_date' => $validated['gift_sent_date'] ?? null,
            'last_gift_name' => $validated['last_gift_name'] ?? null,
            'gift_dates' => $validated['gift_dates'] ?? null,
        ]);

        return redirect()->route('contacts.index')->with('success', 'Contact updated successfully.');
    }

    public function destroy(Contact $contact)
    {
        if ($contact->agency_id !== app('currentAgency')->id) {
            abort(404);
        }

        $contact->delete();

        return redirect()->route('contacts.index')->with('success', 'Contact deleted successfully.');
    }

    public function gifts(Contact $contact)
    {
        if ($contact->agency_id !== app('currentAgency')->id) {
            abort(404);
        }

        $gifts = $contact->gifts()->orderBy('gift_date')->get()->map(function (ContactGift $gift) {
            return [
                'id' => $gift->id,
                'gift_date' => $gift->gift_date?->format('Y-m-d'),
                'gift_date_display' => $gift->gift_date?->format('d-m-Y'),
                'gift_name' => $gift->gift_name,
                'remark' => $gift->remark,
            ];
        });

        return response()->json(['data' => $gifts]);
    }

    public function storeGift(Request $request, Contact $contact)
    {
        if ($contact->agency_id !== app('currentAgency')->id) {
            abort(404);
        }

        $validated = $request->validate([
            'gift_date' => ['required', 'date'],
            'gift_name' => ['required', 'string', 'max:255'],
            'remark' => ['nullable', 'string', 'max:500'],
        ]);

        $gift = $contact->gifts()->create([
            'gift_date' => $validated['gift_date'],
            'gift_name' => $validated['gift_name'],
            'remark' => $validated['remark'] ?? null,
        ]);

        $this->refreshGiftSummary($contact);

        $contact->refresh();

        return response()->json([
            'success' => true,
            'gift' => [
                'id' => $gift->id,
                'gift_date' => $gift->gift_date?->format('Y-m-d'),
                'gift_date_display' => $gift->gift_date?->format('d-m-Y'),
                'gift_name' => $gift->gift_name,
                'remark' => $gift->remark,
            ],
            'summary' => $this->contactSummaryPayload($contact),
        ]);
    }

    public function updateGift(Request $request, Contact $contact, ContactGift $gift)
    {
        if ($contact->agency_id !== app('currentAgency')->id || $gift->contact_id !== $contact->id) {
            abort(404);
        }

        $validated = $request->validate([
            'gift_date' => ['required', 'date'],
            'gift_name' => ['required', 'string', 'max:255'],
            'remark' => ['nullable', 'string', 'max:500'],
        ]);

        $gift->update([
            'gift_date' => $validated['gift_date'],
            'gift_name' => $validated['gift_name'],
            'remark' => $validated['remark'] ?? null,
        ]);

        $this->refreshGiftSummary($contact);

        $contact->refresh();

        return response()->json([
            'success' => true,
            'gift' => [
                'id' => $gift->id,
                'gift_date' => $gift->gift_date?->format('Y-m-d'),
                'gift_date_display' => $gift->gift_date?->format('d-m-Y'),
                'gift_name' => $gift->gift_name,
                'remark' => $gift->remark,
            ],
            'summary' => $this->contactSummaryPayload($contact),
        ]);
    }

    public function destroyGift(Contact $contact, ContactGift $gift)
    {
        if ($contact->agency_id !== app('currentAgency')->id || $gift->contact_id !== $contact->id) {
            abort(404);
        }

        $gift->delete();

        $this->refreshGiftSummary($contact);

        $contact->refresh();

        return response()->json([
            'success' => true,
            'summary' => $this->contactSummaryPayload($contact),
        ]);
    }

    private function refreshGiftSummary(Contact $contact): void
    {
        $latestGift = $contact->gifts()->orderByDesc('gift_date')->first();

        if ($latestGift) {
            $giftDates = $contact->gifts()
                ->orderBy('gift_date')
                ->get()
                ->map(function (ContactGift $gift) {
                    return $gift->gift_date?->format('d M Y');
                })
                ->all();

            $contact->update([
                'sent_gift' => true,
                'gift_sent_date' => $latestGift->gift_date,
                'last_gift_name' => $latestGift->gift_name,
                'gift_dates' => implode(', ', $giftDates),
            ]);
        } else {
            $contact->update([
                'sent_gift' => false,
                'gift_sent_date' => null,
                'last_gift_name' => null,
                'gift_dates' => null,
            ]);
        }
    }

    private function contactSummaryPayload(Contact $contact): array
    {
        return [
            'contact_id' => $contact->id,
            'sent_gift' => (bool) $contact->sent_gift,
            'gift_sent_date' => $contact->gift_sent_date?->format('Y-m-d'),
            'gift_sent_date_display' => $contact->gift_sent_date?->format('d M Y'),
            'last_gift_name' => $contact->last_gift_name,
            'gift_dates' => $contact->gift_dates,
        ];
    }
}
