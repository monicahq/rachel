@props([
  'color' => 'default',
])

@php
  $classes = match ($color) {
    default => 'bg-zinc-400/15 text-zinc-700 dark:bg-zinc-400/40 dark:text-zinc-200 [&_button]:text-zinc-700! dark:[&_button]:text-zinc-200! [&:is(button)]:hover:bg-zinc-400/25 dark:[button]:hover:bg-zinc-400/50',
    'red' => 'bg-red-400/20 text-red-700 dark:bg-red-400/40 dark:text-red-200 [&_button]:text-red-700! dark:[&_button]:text-red-200! [&:is(button)]:hover:bg-red-400/30 dark:[button]:hover:bg-red-400/50',
    'orange' => 'bg-orange-400/20 text-orange-700 dark:bg-orange-400/40 dark:text-orange-200 [&_button]:text-orange-700! dark:[&_button]:text-orange-200! [&:is(button)]:hover:bg-orange-400/30 dark:[button]:hover:bg-orange-400/50',
    'amber' => 'bg-amber-400/25 text-amber-700 dark:bg-amber-400/40 dark:text-amber-200 [&_button]:text-amber-700! dark:[&_button]:text-amber-200! [&:is(button)]:hover:bg-amber-400/40 dark:[button]:hover:bg-amber-400/50',
    'yellow' => 'bg-yellow-400/25 text-yellow-800 dark:bg-yellow-400/40 dark:text-yellow-200 [&_button]:text-yellow-800! dark:[&_button]:text-yellow-200! [&:is(button)]:hover:bg-yellow-400/40 dark:[button]:hover:bg-yellow-400/50',
    'lime' => 'bg-lime-400/25 text-lime-800 dark:bg-lime-400/40 dark:text-lime-200 [&_button]:text-lime-800! dark:[&_button]:text-lime-200! [&:is(button)]:hover:bg-lime-400/35 dark:[button]:hover:bg-lime-400/50',
    'green' => 'bg-green-400/20 text-green-800 dark:bg-green-400/40 dark:text-green-200 [&_button]:text-green-800! dark:[&_button]:text-green-200! [&:is(button)]:hover:bg-green-400/30 dark:[button]:hover:bg-green-400/50',
    'emerald' => 'bg-emerald-400/20 text-emerald-800 dark:bg-emerald-400/40 dark:text-emerald-200 [&_button]:text-emerald-800! dark:[&_button]:text-emerald-200! [&:is(button)]:hover:bg-emerald-400/30 dark:[button]:hover:bg-emerald-400/50',
    'teal' => 'bg-teal-400/20 text-teal-800 dark:bg-teal-400/40 dark:text-teal-200 [&_button]:text-teal-800! dark:[&_button]:text-teal-200! [&:is(button)]:hover:bg-teal-400/30 dark:[button]:hover:bg-teal-400/50',
    'cyan' => 'bg-cyan-400/20 text-cyan-800 dark:bg-cyan-400/40 dark:text-cyan-200 [&_button]:text-cyan-800! dark:[&_button]:text-cyan-200! [&:is(button)]:hover:bg-cyan-400/30 dark:[button]:hover:bg-cyan-400/50',
    'sky' => 'bg-sky-400/20 text-sky-800 dark:bg-sky-400/40 dark:text-sky-200 [&_button]:text-sky-800! dark:[&_button]:text-sky-200! [&:is(button)]:hover:bg-sky-400/30 dark:[button]:hover:bg-sky-400/50',
    'blue' => 'bg-blue-400/20 text-blue-800 dark:bg-blue-400/40 dark:text-blue-200 [&_button]:text-blue-800! dark:[&_button]:text-blue-200! [&:is(button)]:hover:bg-blue-400/30 dark:[button]:hover:bg-blue-400/50',
    'indigo' => 'bg-indigo-400/20 text-indigo-700 dark:bg-indigo-400/40 dark:text-indigo-200 [&_button]:text-indigo-700! dark:[&_button]:text-indigo-200! [&:is(button)]:hover:bg-indigo-400/30 dark:[button]:hover:bg-indigo-400/50',
    'violet' => 'bg-violet-400/20 text-violet-700 dark:bg-violet-400/40 dark:text-violet-200 [&_button]:text-violet-700! dark:[&_button]:text-violet-200! [&:is(button)]:hover:bg-violet-400/30 dark:[button]:hover:bg-violet-400/50',
    'purple' => 'bg-purple-400/20 text-purple-700 dark:bg-purple-400/40 dark:text-purple-200 [&_button]:text-purple-700! dark:[&_button]:text-purple-200! [&:is(button)]:hover:bg-purple-400/30 dark:[button]:hover:bg-purple-400/50',
    'fuchsia' => 'bg-fuchsia-400/20 text-fuchsia-700 dark:bg-fuchsia-400/40 dark:text-fuchsia-200 [&_button]:text-fuchsia-700! dark:[&_button]:text-fuchsia-200! [&:is(button)]:hover:bg-fuchsia-400/30 dark:[button]:hover:bg-fuchsia-400/50',
    'pink' => 'bg-pink-400/20 text-pink-700 dark:bg-pink-400/40 dark:text-pink-200 [&_button]:text-pink-700! dark:[&_button]:text-pink-200! [&:is(button)]:hover:bg-pink-400/30 dark:[button]:hover:bg-pink-400/50',
    'rose' => 'bg-rose-400/20 text-rose-700 dark:bg-rose-400/40 dark:text-rose-200 [&_button]:text-rose-700! dark:[&_button]:text-rose-200! [&:is(button)]:hover:bg-rose-400/30 dark:[button]:hover:bg-rose-400/50',
  };
@endphp

<div class="{{ $classes }} inline-flex items-center rounded-md px-2 py-1 text-sm font-medium whitespace-nowrap">
  {{ $slot }}
</div>
