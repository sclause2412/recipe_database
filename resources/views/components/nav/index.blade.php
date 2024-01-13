 <nav class="border-b border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800" x-data="{ open: false }">

     <div class="mx-auto flex flex-col sm:max-w-7xl sm:flex-row">
         <!-- Menu control -->
         <div class="flex h-16 justify-between sm:w-auto">
             <!-- LOGO -->
             <div class="flex shrink-0 items-center px-4">
                 <a aria-label="{{ __('Back to home page') }}" href="{{ route('welcome') }}">
                     <x-logo.mark class="block h-9 w-auto" />
                 </a>
             </div>
             <!-- HAMBURGER -->
             <div class="flex items-center px-4 sm:hidden" hidden> <button @click="open = ! open"
                     aria-label="{{ __('Menu') }}"
                     class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-900 dark:hover:text-gray-400 dark:focus:bg-gray-900 dark:focus:text-gray-400">
                     <span :class="{ 'hidden': open, 'inline-flex': !open }">
                         <x-icon class="h-6 w-6" name="dots-three" />
                     </span>
                     <span :class="{ 'hidden': !open, 'inline-flex': open }">
                         <x-icon class="h-6 w-6" name="x" />
                     </span>
                 </button>
             </div>
         </div>

         <!-- Menu entries -->
         <div class="relative z-40 w-full sm:static sm:flex sm:space-x-8 sm:px-4">
             <div :class="{ 'block': open, 'hidden': !open }"
                 class="absolute block hidden w-full justify-between bg-white dark:bg-gray-800 sm:static sm:flex">
                 <!-- Left block -->
                 <div class="flex flex-col sm:flex-row sm:space-x-8">
                     {{ $slot }}
                 </div>

                 @if ($right)
                     <!-- Right block -->
                     <div class="block justify-between sm:flex">
                         {{ $right }}
                     </div>
                 @endif
             </div>
         </div>
     </div>
 </nav>
