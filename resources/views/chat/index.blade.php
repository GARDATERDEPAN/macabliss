@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <div class="p-6 border-b">

            <h1 class="text-2xl font-bold">

                Chat Customer

            </h1>

        </div>

        <div>

            @forelse($customers as $chat)

                @php

                    $lastMessage = App\Models\Chat::where(
                        'customer_id',
                        $chat->customer_id
                    )
                    ->latest()
                    ->first();

                    $unreadCount = App\Models\Chat::where(
                        'customer_id',
                        $chat->customer_id
                    )
                    ->where(
                        'sender',
                        'customer'
                    )
                    ->where(
                        'is_read',
                        false
                    )
                    ->count();

                @endphp

                <a
                    href="{{ route('admin.chat.show', $chat->customer_id) }}"
                    class="flex items-center gap-4 px-6 py-4 hover:bg-gray-100 transition border-b">

                    <!-- AVATAR -->
                    <div
                        class="w-14 h-14 rounded-full
                               bg-red-100
                               flex items-center justify-center
                               text-red-500
                               text-2xl font-bold
                               shrink-0">

                        {{ strtoupper(substr($chat->customer->name, 0, 1)) }}

                    </div>

                    <!-- NAMA + NOMOR + PESAN -->
                    <div class="flex-1 min-w-0">

                        <h3
                            class="font-bold text-lg truncate">

                            {{ $chat->customer->name }}

                        </h3>

                        <div
                            class="flex items-center gap-1
                                text-sm text-gray-400 mb-1">

                            <i
                                data-lucide="phone"
                                class="w-3.5 h-3.5">
                            </i>

                            <span class="truncate">

                                {{ $chat->customer->phone ?? '-' }}

                            </span>

                        </div>

                        <p
                            class="text-gray-500 truncate">

                            {{ $lastMessage?->message }}

                        </p>

                    </div>

                    <!-- JAM + BADGE -->
                    <div
                        class="flex flex-col
                               items-end
                               gap-2
                               shrink-0">

                        <span
                            class="text-sm text-gray-500">

                            {{
                                $lastMessage
                                ? $lastMessage->created_at->format('H:i')
                                : ''
                            }}

                        </span>

                        @if($unreadCount > 0)

                            <span
                                class="w-7 h-7
                                       rounded-full
                                       bg-red-500
                                       text-white
                                       text-xs font-bold
                                       flex items-center justify-center">

                                {{ $unreadCount }}

                            </span>

                        @endif

                    </div>

                </a>

            @empty

                <div
                    class="text-center py-10 text-gray-400">

                    Belum ada chat customer

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection