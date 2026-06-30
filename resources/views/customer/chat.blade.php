@extends('layouts.customer')

@section('content')

<div class="max-w-3xl mx-auto p-6">

    <div class="bg-white rounded-2xl shadow overflow-hidden">

        <!-- HEADER -->
        <div class="bg-red-400 text-white p-4">

            <h2 class="font-semibold text-lg">
                Chat Admin Macabliss
            </h2>

            <p class="text-sm opacity-90">
                Tanyakan apa saja mengenai pesanan Anda.
            </p>

        </div>

        <!-- CHAT BODY -->
        <div
            id="chat-box"
            class="h-[448px] overflow-y-auto p-4 bg-gray-50 space-y-3">

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
                    {{ $message->sender == 'customer'
                        ? 'justify-end'
                        : 'justify-start'
                    }}">

                    <div
                        class="
                        max-w-[75%]
                        px-4 py-2
                        rounded-2xl
                        text-sm
                        shadow-sm
                        break-words
                        {{ $message->sender == 'customer'
                            ? 'bg-red-400 text-white'
                            : 'bg-white border' }}
                    ">

                        @if($message->image)

                            <img
                                src="{{ asset('storage/'.$message->image) }}"
                                class="max-w-[220px] rounded-xl mb-2 cursor-pointer"
                                onclick="window.open(this.src)">

                        @endif

                        {{ $message->message }}

                        <div class="flex items-center gap-1 text-[10px] mt-1 opacity-70">

                            {{ $message->created_at->format('H:i') }}

                            @if($message->sender == 'customer')

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

                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center text-gray-400 py-10">

                    Belum ada pesan.

                </div>

            @endforelse

        </div>

        <!-- FORM -->
        <form
            id="chat-form"
            enctype="multipart/form-data"
            class="border-t p-4 flex gap-3 items-center">

            @csrf

            <label
                class="cursor-pointer
                    bg-gray-100
                    hover:bg-gray-200
                    w-14 h-14
                    rounded-2xl
                    flex items-center justify-center">

                <i data-lucide="image" class="w-5 h-5"></i>

                <input
                    type="file"
                    id="image"
                    accept="image/*"
                    class="hidden">

            </label>

            <input
                type="text"
                id="message"
                class="
                    flex-1
                    h-14
                    border
                    rounded-2xl
                    px-5
                "
                placeholder="Ketik pesan...">

            <button
                type="submit"
                class="
                    h-14
                    min-w-[120px]
                    bg-red-400
                    hover:bg-red-500
                    text-white
                    font-semibold
                    rounded-2xl
                    transition
                ">

                Kirim

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

window.onload = function () {

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
            "{{ route('customer.chat.send') }}",
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
        "{{ route('customer.chat.fetch') }}"
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
                    message.sender == 'customer'
                    ? 'justify-end'
                    : 'justify-start'
                }">

                    <div class="
                        max-w-[75%]
                        px-4 py-2
                        rounded-2xl
                        text-sm
                        shadow-sm
                        break-words
                        ${
                            message.sender == 'customer'
                            ? 'bg-red-400 text-white'
                            : 'bg-white border'
                        }
                    ">

                        ${
                            message.image
                            ? `
                                <img
                                    src="/storage/${message.image}"
                                    class="max-w-[220px] rounded-xl mb-2 cursor-pointer"
                                    onclick="window.open(this.src)">
                            `
                            : ''
                        }

                        ${message.message ?? ''}

                        <div class="flex items-center gap-1 text-[10px] mt-1 opacity-70">

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
                                message.sender === 'customer'
                                ? (
                                    message.is_read
                                    ? '<span class="text-blue-400 font-bold">✓✓</span>'
                                    : '<span>✓</span>'
                                )
                                : ''
                            }

                        </div>

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