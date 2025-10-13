<x-layouts.instance :title="__('Instance management')">
  <div class="mx-auto w-5xl space-y-6 py-10">
    <!-- stats -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
      <x-box>
        <p class="mb-1 text-sm text-gray-500">Total accounts</p>
        <p class="text-2xl font-semibold">1000</p>
      </x-box>

      <x-box>
        <p class="mb-1 text-sm text-gray-500">New accounts last 30 days</p>
        <p class="text-2xl font-semibold">1000</p>
      </x-box>

      <x-box>
        <p class="mb-1 text-sm text-gray-500">Active accounts last 30 days</p>
        <p class="text-2xl font-semibold">1000</p>
      </x-box>
    </div>

    <x-box title="Latest accounts" description="These are the latest accounts created on the instance." padding="p-0">
      <x-table>
        <x-table.rows>
          <x-table.row>
            <x-table.cell>
              <x-keyboard>#43</x-keyboard>
            </x-table.cell>

            <x-table.cell>
              <span class="block truncate text-ellipsis"><x-link href="{{ route('instances.accounts.show') }}">John Doe ({{ 'john@doe.com' }})</x-link></span>
            </x-table.cell>

            <x-table.cell>12/12/2025 12:32pm</x-table.cell>

            <x-table.cell>
              <x-badge color="green">Paid</x-badge>
            </x-table.cell>
          </x-table.row>
          <x-table.row>
            <x-table.cell>
              <x-keyboard>#43</x-keyboard>
            </x-table.cell>

            <x-table.cell>
              <span class="block truncate text-ellipsis"><x-link href="{{ route('instances.accounts.show') }}">John Doe ({{ 'john@doe.com' }})</x-link></span>
            </x-table.cell>

            <x-table.cell>12/12/2025 12:32pm</x-table.cell>

            <x-table.cell>
              <x-badge color="green">Paid</x-badge>
            </x-table.cell>
          </x-table.row>
        </x-table.rows>
      </x-table>
    </x-box>
  </div>
</x-layouts.instance>
