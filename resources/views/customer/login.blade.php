@extends('layouts.customer')

@section('content')

<div class="min-h-screen flex items-center justify-center py-10">

    <div class="w-full max-w-md">

        <!-- TITLE -->
        <div class="text-center mb-6">

            <div
                class="w-20 h-20
                       mx-auto
                       bg-red-100
                       rounded-full
                       flex items-center
                       justify-center
                       mb-5"
            >

                <i
                    data-lucide="user"
                    class="w-10 h-10 text-red-500"
                ></i>

            </div>


            <!-- LOGO -->
            <div
                class="flex
                       items-center
                       justify-center
                       gap-2
                       mb-3"
            >

                <i
                    data-lucide="cookie"
                    class="w-6 h-6 text-red-500"
                ></i>

                <span
                    class="text-2xl
                           font-bold
                           text-gray-800"
                >
                    Macabliss
                </span>

            </div>


            <p
                class="text-gray-500
                       leading-7"
            >
                Login untuk melanjutkan
                pemesanan Anda di Macabliss
            </p>

        </div>


        <!-- CARD -->
        <div
            class="bg-white
                   rounded-3xl
                   shadow-md
                   border border-gray-100
                   p-8"
        >

            @if ($errors->any())

                <div
                    class="bg-red-50
                           border border-red-200
                           text-red-500
                           p-4
                           rounded-2xl
                           mb-6"
                >

                    {{ $errors->first() }}

                </div>

            @endif


            <form
                method="POST"
                action="{{ route('customer.login') }}"
                class="space-y-6"
            >

                @csrf


                <!-- NAMA -->
                <div>

                    <label
                        class="block
                               mb-2
                               text-sm
                               font-semibold
                               text-gray-700"
                    >
                        Nama
                    </label>

                    <input
                        type="text"
                        name="name"
                        required

                        placeholder="Masukkan nama"

                        class="w-full
                               h-12
                               px-4
                               rounded-xl
                               border border-gray-200
                               bg-white
                               placeholder:text-gray-400
                               focus:outline-none
                               focus:ring-2
                               focus:ring-red-200
                               focus:border-red-400
                               transition"
                    >

                </div>


                <!-- PHONE -->
                <div>

                    <label
                        class="block
                               mb-2
                               text-sm
                               font-semibold
                               text-gray-700"
                    >
                        Nomor Handphone
                    </label>

                    <input
                        type="text"
                        name="phone"
                        required

                        placeholder="08xxxxxxxxxx"

                        class="w-full
                               h-12
                               px-4
                               rounded-xl
                               border border-gray-200
                               bg-white
                               placeholder:text-gray-400
                               focus:outline-none
                               focus:ring-2
                               focus:ring-red-200
                               focus:border-red-400
                               transition"
                    >

                </div>


                <!-- BUTTON -->
                <button
                    type="submit"

                    class="w-full
                           h-12
                           bg-red-500
                           hover:bg-red-600
                           text-white
                           rounded-xl
                           font-semibold
                           shadow-sm
                           transition
                           duration-200"
                >

                    Masuk ke Macabliss

                </button>

            </form>

        </div>

    </div>

</div>

@endsection