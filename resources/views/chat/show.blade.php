@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-3xl shadow overflow-hidden">

        <!-- HEADER -->
        <div class="bg-red-400 text-white p-6">

            <h2 class="text-xl font-bold">

                {{ $customer->name }}

            </h2>

            <p class="text-sm opacity-90">

                Customer Macabliss

            </p>

        </div>

        <!-- CHAT -->
        <div
            id="chat-box"
            class="
            h-[60vh]
            md:h-[420px]
            overflow-y-auto
            p-3
            md:p-6
            bg-gray-50
            space-y-4
        ">

            @php
                $lastDate = null;
            @endphp

            @forelse($messages as $message)

                @php
                    $currentDate = $message->created_at->format('Y-m-d');
                @endphp

                @if($lastDate != $currentDate)

                    <div class="flex justify-center my-4">

                        <span
                            class="bg-white border shadow-sm px-4 py-1 rounded-full text-xs text-gray-500">

                            @if($message->created_at->isToday())

                                Hari Ini

                            @elseif($message->created_at->isYesterday())

                                Kemarin

                            @else

                                {{ $message->created_at->translatedFormat('d F Y') }}

                            @endif

                        </span>

                    </div>

                    @php
                        $lastDate = $currentDate;
                    @endphp

                @endif

                <div
                    class="flex
                    {{ $message->sender == 'admin'
                        ? 'justify-end'
                        : 'justify-start' }}">

                    <div
                        class="
                        max-w-[85%] md:max-w-[75%]
                        px-4 py-2
                        rounded-2xl
                        text-sm
                        shadow-sm
                        break-words
                        {{ $message->sender == 'admin'
                            ? 'bg-red-400 text-white'
                            : 'bg-white border' }}
                    ">

                        @if($message->image)

                            <img
                                src="{{ asset('storage/'.$message->image) }}"
                                class="
                                    max-w-[180px]
                                    md:max-w-[220px]
                                    rounded-xl
                                    mb-2
                                    cursor-pointer
                                "
                                onclick="window.open(this.src)">

                        @endif

                        {{ $message->message }}

                        <small
                            class="flex items-center gap-1 text-xs opacity-70 mt-1">

                            {{ $message->created_at->format('H:i') }}

                            @if($message->sender == 'admin')

                                @if($message->is_read)

                                    <span class="text-blue-400 font-bold">
                                        ✓✓
                                    </span>

                                @else

                                    <span>
                                        ✓
                                    </span>

                                @endif

                            @endif

                        </small>

                    </div>

                </div>

            @empty

                <div class="text-center text-gray-400 py-10">

                    Belum ada pesan.

                </div>

            @endforelse

        </div>

        <!-- QUICK REPLY -->
        <div class="
            px-3
            md:px-4
            pt-3
            flex
            flex-wrap
            gap-2
            bg-white
            border-t
            overflow-x-auto
        ">

            <button
                type="button"
                onclick="setQuickReply('Halo Kak 👋')"
                class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-full">

                Halo Kak 👋

            </button>

            <button
                type="button"
                onclick="setQuickReply('Pesanan Anda sedang diproses ya kak 😊')"
                class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-full">

                Pesanan Sedang Diproses

            </button>

            <button
                type="button"
                onclick="setQuickReply('Pesanan Anda sudah dikirim dan sedang dalam perjalanan 🚚')"
                class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-full">

                Pesanan Sedang Dikirim

            </button>

            <button
                type="button"
                onclick="setQuickReply('Terima kasih sudah berbelanja di Macabliss ❤️')"
                class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-full">

                Terima Kasih

            </button>

            <button
                type="button"
                onclick="setQuickReply('Jangan lupa memberikan rating dan ulasan untuk pesanan Anda ya kak ⭐😊')"
                class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-full">

                ⭐ Minta Rating

            </button>

        </div>

        <!-- FORM -->
        <form
            id="chat-form"
            enctype="multipart/form-data"
            class="
                p-3
                md:p-4
                flex
                items-center
                gap-3
                border-t
            ">

            @csrf

            <label
                class="
                    flex-shrink-0
                    cursor-pointer
                    bg-gray-100
                    hover:bg-gray-200
                    w-14
                    h-14
                    rounded-2xl
                    flex
                    items-center
                    justify-center
                    transition
                ">

                <i
                    data-lucide="image"
                    class="w-6 h-6 text-gray-500">
                </i>

                <input
                    type="file"
                    id="image"
                    accept="image/*"
                    class="hidden">

            </label>

            <input
                type="text"
                id="message"
                name="message"
                placeholder="Ketik balasan..."
                class="
                    flex-1
                    h-14
                    border-2
                    border-gray-300
                    rounded-2xl
                    px-5
                    text-sm
                    md:text-base
                    focus:outline-none
                    focus:border-red-400
                    min-w-0
                ">

            <button
                type="submit"
                class="
                    flex-shrink-0
                    w-14
                    h-14
                    bg-red-400
                    hover:bg-red-500
                    text-white
                    rounded-2xl
                    flex
                    items-center
                    justify-center
                    transition
                ">

                <i
                    data-lucide="send-horizontal"
                    class="w-5 h-5">
                </i>

            </button>

        </form>

        <div
            id="file-name"
            class="hidden px-4 pb-4 text-sm text-gray-500">
        </div>

    </div>

</div>

<script>

const chatBox =
    document.getElementById('chat-box');

const imageInput =
    document.getElementById('image');

const fileName =
    document.getElementById('file-name');


imageInput.addEventListener(
    'change',
    function() {

        if (this.files.length) {

            fileName.innerHTML =
                '📷 ' +
                this.files[0].name;

            fileName.classList.remove(
                'hidden'
            );

        } else {

            fileName.classList.add(
                'hidden'
            );

        }

    }
);


function setQuickReply(text) {

    document
        .getElementById('message')
        .value = text;

    document
        .getElementById('message')
        .focus();

}


window.onload = function () {

    lucide.createIcons();

    loadChat(true);

};


document
.getElementById('chat-form')
.addEventListener('submit', async function(e) {

    e.preventDefault();

    let message =
        document.getElementById('message');

    let image =
        document.getElementById('image');

    if (
        !message.value.trim()
        && !image.files.length
    ) return;

    try {

        const formData =
            new FormData();

        formData.append(
            'message',
            message.value
        );

        if (image.files.length) {

            formData.append(
                'image',
                image.files[0]
            );

        }

        await fetch(
            "{{ route('admin.chat.send', $customer->id) }}",
            {

                method: 'POST',

                headers: {

                    'X-CSRF-TOKEN':
                        '{{ csrf_token() }}',

                    'Accept':
                        'application/json'

                },

                body: formData

            }

        );

        message.value = '';

        image.value = '';

        fileName.classList.add(
            'hidden'
        );

        loadChat(true);

    } catch (error) {

        alert('Gagal mengirim pesan');

    }

});


function formatChatDate(dateString) {

    const date = new Date(dateString);

    const today = new Date();

    const yesterday = new Date();

    yesterday.setDate(
        today.getDate() - 1
    );

    if (
        date.toDateString()
        === today.toDateString()
    ) {

        return 'Hari Ini';

    }

    if (
        date.toDateString()
        === yesterday.toDateString()
    ) {

        return 'Kemarin';

    }

    return date.toLocaleDateString(
        'id-ID',
        {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        }
    );

}


function loadChat(forceScroll = false) {

    const isNearBottom =

        chatBox.scrollHeight
        - chatBox.scrollTop
        - chatBox.clientHeight
        < 100;


    fetch(
        "{{ route('chat.fetch', $customer->id) }}"
    )

    .then(response => response.json())

    .then(data => {

        let html = '';

        let lastDate = '';

        if (data.length === 0) {

            html = `

                <div class="text-center text-gray-400 py-10">

                    Belum ada pesan.

                </div>

            `;

        } else {

            data.forEach(message => {

                const currentDate =
                    formatChatDate(
                        message.created_at
                    );

                if (
                    currentDate !== lastDate
                ) {

                    html += `

                    <div class="flex justify-center my-4">

                        <span
                            class="bg-white border shadow-sm px-4 py-1 rounded-full text-xs text-gray-500">

                            ${currentDate}

                        </span>

                    </div>

                    `;

                    lastDate = currentDate;

                }

                html += `

                <div class="flex ${
                    message.sender == 'admin'
                    ? 'justify-end'
                    : 'justify-start'
                }">

                    <div class="
                        max-w-[85%] md:max-w-[75%]
                        px-4 py-2
                        rounded-2xl
                        text-sm
                        shadow-sm
                        break-words
                        ${
                            message.sender == 'admin'
                            ? 'bg-red-400 text-white'
                            : 'bg-white border'
                        }
                    ">

                        ${
                            message.image
                            ? `
                                <img
                                    src="/storage/${message.image}"
                                    class="
                                    max-w-[180px]
                                    md:max-w-[220px]
                                    rounded-xl
                                    mb-2
                                    cursor-pointer
                                "
                                    onclick="window.open(this.src)">
                            `
                            : ''
                        }

                        ${message.message ?? ''}

                        <small class="flex items-center gap-1 text-xs opacity-70 mt-1">

                            ${
                                new Date(message.created_at)
                                .toLocaleTimeString(
                                    'id-ID',
                                    {
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    }
                                )
                            }

                            ${
                                message.sender === 'admin'
                                ? (
                                    message.is_read
                                    ? '<span class="text-blue-400 font-bold">✓✓</span>'
                                    : '<span>✓</span>'
                                )
                                : ''
                            }

                        </small>

                    </div>

                </div>

                `;

            });

        }

        chatBox.innerHTML = html;

        if (isNearBottom || forceScroll) {

            chatBox.scrollTo({

                top: chatBox.scrollHeight,

                behavior: 'smooth'

            });

        }

    });

}


setInterval(

    () => loadChat(false),

    2000

);

</script>

@endsection