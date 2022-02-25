<div class="flex-col px-12 space-y-4">
  <div class="flex items-center justify-between">
    <strong class="text-lg font-bold md:text-2xl">Orders</strong>
  </div>

  <div class="space-y-4">
    <x-hub::table>
      <x-slot name="toolbar">
        <div class="p-4 space-y-4 border-b" x-data="{ filtersVisible: true }">
          <div class="flex items-center space-x-4">
            <div class="flex items-center w-full space-x-4">
              <x-hub::input.text placeholder="Search by reference or customer name" class="py-2" wire:model.debounce.400ms="search" />

              <x-hub::button theme="gray" class="inline-flex items-center" @click.prevent="filtersVisible = !filtersVisible">
                <x-hub::icon ref="filter" class="w-4 mr-1" />
                Filter
              </x-hub::button>
            </div>
          </div>

          <div class="grid grid-cols-4 gap-4" x-show="filtersVisible" x-cloak>
            <x-hub::input.group label="Status" for="status">
              <x-hub::input.select wire:model="filters.status">
                <option value>Any</option>
                @foreach($this->orders->facets->get('status') as $facet)
                  <option value="{{ $facet->value }}">{{ $facet->value }}</option>
                @endforeach
              </x-hub::input.select>
            </x-hub::input.group>

            @foreach($this->availableFilters as $filter)
              <x-hub::input.group :label="$filter->heading" for="{{ $filter->field }}">
                <x-hub::input.select wire:model="filters.{{ $filter->field }}">
                  <option value>Any</option>
                  @foreach($this->orders->facets->get($filter->field) as $facet)
                    <option value="{{ $facet->value }}">{{ $facet->value }}</option>
                  @endforeach
                </x-hub::input.select>
              </x-hub::input.group>
            @endforeach

            <x-hub::input.group label="From Date" for="from_date">
              <x-hub::input.datepicker wire:model="filters.from" />
            </x-hub::input.group>

            <x-hub::input.group label="To Date" for="to_date">
              <x-hub::input.datepicker wire:model="filters.to" />
            </x-hub::input.group>

          </div>
        </div>
      </x-slot>
      <x-slot name="head">
        <x-hub::table.heading>
          Status
        </x-hub::table.heading>
        <x-hub::table.heading>
          Reference
        </x-hub::table.heading>
        <x-hub::table.heading>
          Customer
        </x-hub::table.heading>
        <x-hub::table.heading>
          Total
        </x-hub::table.heading>
        @foreach($this->columns as $column)
          <x-hub::table.heading>
            {{ $column->heading }}
          </x-hub::table.heading>
        @endforeach
        <x-hub::table.heading>
          Date
        </x-hub::table.heading>
        <x-hub::table.heading>
          Time
        </x-hub::table.heading>
        <x-hub::table.heading></x-hub::table.heading>
      </x-slot>
      <x-slot name="body">
        @forelse($this->orders->items as $order)
          <x-hub::table.row wire:key="row-{{ $order->id }}">
            <x-hub::table.cell>
              {{ $order->statusLabel }}
            </x-hub::table.cell>
            <x-hub::table.cell>
              {{ $order->reference }}
            </x-hub::table.cell>
            <x-hub::table.cell>
              {{ $order->billingAddress->first_name }}
            </x-hub::table.cell>
            <x-hub::table.cell>
              {{ $order->total->formatted() }}
            </x-hub::table.cell>
            @foreach($this->columns as $column)
              <x-hub::table.cell>
                @if($column->callback)
                  {{ $column->getValue($order) }}
                @endif
              </x-hub::table.cell>
            @endforeach
            <x-hub::table.cell>
              @if($order->placed_at)
                {{ $order->placed_at->format('jS M Y') }}
              @else
                {{ $order->created_at->format('jS M Y') }}
              @endif
            </x-hub::table.cell>
            <x-hub::table.cell>
              @if($order->placed_at)
                {{ $order->placed_at->format('h:ma') }}
              @else
                {{ $order->created_at->format('h:ma') }}
              @endif
            </x-hub::table.cell>
            <x-hub::table.cell>
              <a href="{{ route('hub.orders.show', $order->id) }}" class="text-indigo-500 hover:underline">View</a>
            </x-hub::table.cell>
          </x-hub::table.row>
        @empty

        @endforelse
      </x-slot>
    </x-hub::table>
    <div>
      {{ $this->orders->items->links() }}
    </div>
  </div>
</div>
