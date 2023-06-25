<!DOCTYPE html>
<html lang="fa-IR" class="no-js">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <title>{{ str_replace('-', ' ', strtoupper(config('app.name'))) }}</title>
    <!-- Included CSS Files -->
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>

<div class="font-sans bg-white flex flex-col min-h-screen w-full">
    <div>
        <div class="bg-gray-200 px-4 py-4">
            <div
                class="w-full max-w-6xl mx-auto flex items-center justify-between">
                <div>
                    <a href="#" class="inline-block py-2 text-gray-800 text-2xl font-bold">MD</a>
                </div>

                <div>
                    <a
                        href="{{ url('docs') }}"
                        target="_blank"
                        class="inline-block py-1 md:py-4 text-gray-500 hover:text-gray-600 mr-6"
                    >Documents</a>
                    <a
                        href="{{ route('download-postman-collection') }}"
                        target="_blank"
                        class="inline-block py-2 px-4 text-gray-700 bg-white hover:bg-gray-100 rounded-lg"
                    >Download Postman</a>
                </div>
            </div>
        </div>

        <div class="bg-gray-200 overflow-hidden">
            <div class="px-4 py-16">
                <div class="relative w-full max-w-2xl mx-auto text-center">
                    <h1
                        class="font-bold text-gray-700 text-xl sm:text-2xl md:text-5xl leading-tight mb-6"
                    >
                        {{ str_replace('-', ' ', strtoupper(config('app.name'))) }}'s API
                    </h1>

                    <p class="text-gray-600 md:text-xl md:px-18">
                        MD offers you the best software services you want.
                    </p>

                    <div
                        class="h-40 w-40 rounded-full bg-blue-800 absolute right-0 bottom-0 -mb-64 -mr-48"
                    ></div>

                    <div
                        class="h-5 w-5 rounded-full bg-yellow-500 absolute top-0 right-0 -mr-40 mt-32"
                    ></div>
                </div>
            </div>

            <svg
                class="fill-current bg-gray-200 text-white hidden md:block"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 1440 320">
                <path
                    fill-opacity="1"
                    d="M0,64L120,85.3C240,107,480,149,720,149.3C960,149,1200,107,1320,85.3L1440,64L1440,320L1320,320C1200,320,960,320,720,320C480,320,240,320,120,320L0,320Z"
                ></path>
            </svg>
        </div>

        <div
            class="max-w-4xl mx-auto bg-white shadow-lg relative z-20 hidden md:block"
            style="margin-top: -320px; border-radius: 20px;">
            <div
                class="h-20 w-20 rounded-full bg-yellow-500 absolute top-0 left-0 -ml-10 -mt-10"
                style="z-index: -1;"></div>

            <div
                class="h-5 w-5 rounded-full bg-blue-500 absolute top-0 left-0 -ml-32 mt-12"
                style="z-index: -1;"></div>
        </div>

        <p class="text-center p-4 text-gray-600 mt-10">
            Contact
            <a
                class="border-b text-blue-500"
                href="https://twitter.com/mithicher"
                target="_blank"
            >mdhesari99@gmail.com</a> For any questions on development.
        </p>
    </div>
</div>

</body>
</html>
