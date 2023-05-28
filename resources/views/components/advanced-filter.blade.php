@props(['filterGroups'])

<div x-data="advancedFilter()" x-init="init()">
    <div class="w-full flex flex-col bg-white shadow rounded p-5">
        <div class="filterable">
            <div class="flex items-center">
                <div class="text-left">
                    <span>Enregitrements qui correspondent à</span>
                    <select class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" x-model="query.filter_match">
                        <option value="and">tous les</option>
                        <option value="or">au moins un des</option>
                    </select>
                    <span>éléments suivants:</span>
                </div>
            </div>
            <div class="py-3">
                <div class="filter">
                    <template x-for="(f, i) in filterCandidates" :key="i">
                        <div>
                            <div class="flex mb-1">
                                <div class="pr-2 w-3/12">
                                    <div class="form-group">
                                        <select class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" @change="selectColumn(f, i, $event)">
                                            <option selected>Sélectionner un filtre</option>
                                            <template x-for="(group, i) in filterGroups" :key="i">
                                                <optgroup :label="group.name">
                                                    <template x-for="x in group.filters" :key="x.name">
                                                        <option
                                                            :value="JSON.stringify(x); i"
                                                            :selected="f.column && x.name === f.column.name"
                                                            x-text="x.title"
                                                        ></option>
                                                    </template>
                                                </optgroup>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <template x-if="f.column">
                                    <div class="pr-2 w-3/12">
                                        <div class="form-group">
                                            <select class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" @change="selectOperator(f, i, $event)">
                                                <template x-for="y in fetchOperators(f)" :key="y.name">
                                                    <option
                                                        :value="JSON.stringify(y)"
                                                        :selected="f.operator && y.name === f.operator.name"
                                                        x-text="y.title"
                                                    ></option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="f.column && f.operator">
                                    <div>
                                        <template x-if="f.operator.component === 'single'">
                                            <div class="filter-full">
                                                <input :type="f.operator.input ? f.operator.input : 'text'"
                                                       class="fblock w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                       x-model="f.query_1"/>
                                            </div>
                                        </template>
                                        <template x-if="f.operator.component === 'double'">
                                            <div class="flex items-center space-x-2">
                                                <div class="filter-query_1">
                                                    <input :type="f.operator.input ? f.operator.input : 'text'"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" x-model="f.query_1"/>
                                                </div>
                                                <div class="filter-query_2">
                                                    <input :type="f.operator.input ? f.operator.input : 'text'"
                                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" x-model="f.query_2"/>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="f.operator.component === 'datetime_1'">
                                            <div class="flex items-center space-x-2">
                                                <div class="filter-query_1">
                                                    <input type="text" class="form-input w-full" x-model="f.query_1"/>
                                                </div>
                                                <div class="filter-query_2">
                                                    <select class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" x-model="f.query_2">
                                                        <option>heures</option>
                                                        <option>jours</option>
                                                        <option>mois</option>
                                                        <option>années</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="f.operator.component === 'datetime_2'">
                                            <div class="filter-query_2">
                                                <select class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" x-model="f.query_1">
                                                    <option value="yesterday">hier</option>
                                                    <option value="today">aujourd'hui</option>
                                                    <option value="tomorrow">demain</option>
                                                    <option value="last_month">le mois passé</option>
                                                    <option value="this_month">ce mois</option>
                                                    <option value="next_month">le mois prochain</option>
                                                    <option value="last_year">l'année passée</option>
                                                    <option value="this_year">cette année</option>
                                                    <option value="next_year">l'année prochaine</option>
                                                </select>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                <template x-if="f">
                                    <div class="filter-remove ml-4">
                                        <x-danger-button x-on:click="removeFilter(f, i)">
                                            <x-heroicon-o-trash class="w-5 h-5"></x-heroicon-o-trash>
                                        </x-danger-button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                    <div class="filter-controls flex items-center space-x-2 mt-4">
                        <x-button x-on:click="addFilter">+</x-button>
                        <x-loading-button class="btn-primary" x-on:click="applyFilter">Appliquer les filtres</x-loading-button>
                        <template x-if="appliedFilters.length">
                            <x-button class="btn-accent" x-on:click="resetFilter">Réintialiser</x-button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
    <script type="text/javascript">
        function advancedFilter() {
            return {
                filterGroups: @json($filterGroups),
                appliedFilters: [],
                filterCandidates: [],
                query: {
                    filter_match: "and",
                },
                init() {
                    this.filterCandidates = JSON.parse(window.sessionStorage.getItem('delivery_stats.filterCandidates')) || []

                    if (this.filterCandidates.length === 0) {
                        this.addFilter()
                    }
                },
                fetchOperators(f) {
                    return this.availableOperators().filter(operator => {
                        if (f.column && operator.parent.includes(f.column.type)) {
                            return operator;
                        }
                    });
                },
                resetFilter() {
                    this.appliedFilters.splice(0);
                    this.filterCandidates.splice(0);
                    window.sessionStorage.removeItem('delivery_stats.filterCandidates')
                    window.dispatchEvent(new CustomEvent('apply-filters', { detail: { filter: [...this.getFilters(),], ...this.query}}))
                    this.addFilter();
                },
                applyFilter() {
                    this.appliedFilters = JSON.parse(JSON.stringify(this.filterCandidates))
                    window.sessionStorage.setItem('delivery_stats.filterCandidates', JSON.stringify(this.filterCandidates))
                    window.dispatchEvent(new CustomEvent('apply-filters', { detail: { filter: [...this.getFilters(),], ...this.query}}))
                },
                removeFilter(f, i) {
                    this.filterCandidates.splice(i, 1);
                },
                selectOperator(f, i, e) {
                    let value = e.target.value;

                    if (value.length === 0) {
                        this.filterCandidates[i].operator = value
                        return;
                    }

                    let obj = JSON.parse(value);

                    this.filterCandidates[i].operator = obj
                    this.filterCandidates[i].query_1 = null;
                    this.filterCandidates[i].query_2 = null;

                    switch (obj.name) {
                        case "in_the_past":
                        case "in_the_next":
                            this.filterCandidates[i].query_1 = 30;
                            this.filterCandidates[i].query_2 = "jours";
                            break;
                        case "in_the_period":
                            this.filterCandidates[i].query_1 = "aujourd'hui";
                            break;
                    }
                },
                selectColumn(f, i, e) {
                    let value = e.target.value;

                    if (value.length === 0) {
                        this.filterCandidates[i].column = value
                        return;
                    }

                    let obj = JSON.parse(value);

                    this.filterCandidates[i].column = obj

                    switch (obj.type) {
                        case "numeric":
                            this.filterCandidates[i].operator = this.availableOperators()[4];
                            this.filterCandidates[i].query_1 = null;
                            this.filterCandidates[i].query_2 = null;
                            break;
                        case "string":
                            this.filterCandidates[i].operator = this.availableOperators()[0];
                            this.filterCandidates[i].query_1 = null;
                            this.filterCandidates[i].query_2 = null;
                            break;
                        case "datetime":
                            this.filterCandidates[i].operator = this.availableOperators()[5];
                            this.filterCandidates[i].query_1 = null;
                            this.filterCandidates[i].query_2 = null;
                            break;
                        case "counter":
                            this.filterCandidates[i].operator = this.availableOperators()[14];
                            this.filterCandidates[i].query_1 = null;
                            this.filterCandidates[i].query_2 = null;
                            break;
                    }
                },
                addFilter() {
                    this.filterCandidates.push({
                        column: "",
                        operator: "",
                        query_1: null,
                        query_2: null
                    });
                },
                getFilters() {
                    return this.appliedFilters.map((filter) => ({
                        column: filter.column.name,
                        operator: filter.operator.name,
                        query_1: filter.query_1,
                        query_2: filter.query_2
                    }));
                },
                availableOperators() {
                    return [
                        {
                            title: "égal a",
                            name: "equal_to",
                            parent: ["numeric", "string"],
                            component: "single"
                        },
                        {
                            title: "différent de",
                            name: "not_equal_to",
                            parent: ["numeric", "string"],
                            component: "single"
                        },
                        {
                            title: "inferieur à",
                            name: "less_than",
                            parent: ["numeric"],
                            component: "single"
                        },
                        {
                            title: "superieur à",
                            name: "greater_than",
                            parent: ["numeric"],
                            component: "single"
                        },
                        {
                            title: "entre",
                            name: "between",
                            parent: ["numeric"],
                            component: "double"
                        },
                        {
                            title: "entre",
                            name: "between",
                            parent: ["datetime"],
                            component: "double",
                            input: 'date'
                        },
                        {
                            title: "hors de",
                            name: "not_between",
                            parent: ["numeric"],
                            component: "double"
                        },
                        {
                            title: "contient",
                            name: "contains",
                            parent: ["string"],
                            component: "single"
                        },
                        {
                            title: "commence par",
                            name: "starts_with",
                            parent: ["string"],
                            component: "single"
                        },
                        {
                            title: "se termine par",
                            name: "ends_with",
                            parent: ["string"],
                            component: "single"
                        },
                        {
                            title: "dans les derniers",
                            name: "in_the_past",
                            parent: ["datetime"],
                            component: "datetime_1"
                        },
                        {
                            title: "dans les prochains",
                            name: "in_the_next",
                            parent: ["datetime"],
                            component: "datetime_1"
                        },
                        {
                            title: "dans la période",
                            name: "in_the_period",
                            parent: ["datetime"],
                            component: "datetime_2"
                        },
                        {
                            title: "égal a",
                            name: "equal_to_count",
                            parent: ["counter"],
                            component: "single"
                        },
                        {
                            title: "différent de",
                            name: "not_equal_to_count",
                            parent: ["counter"],
                            component: "single"
                        },
                        {
                            title: "inferieur à",
                            name: "less_than_count",
                            parent: ["counter"],
                            component: "single"
                        },
                        {
                            title: "superieur à",
                            name: "greater_than_count",
                            parent: ["counter"],
                            component: "single"
                        }
                    ];
                }
            }
        }
    </script>
@endpush
