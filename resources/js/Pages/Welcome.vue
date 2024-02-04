<script setup>
import axios from "axios";
import { ref, computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import {
    Combobox,
    ComboboxLabel,
    ComboboxInput,
    ComboboxOptions,
    ComboboxOption,
    ComboboxButton,
} from "@headlessui/vue";
import brands from "../brands.json";

const queryBrands = ref("");
const queryModels = ref("");
const queryYears = ref("");
const brandsArray = Object.keys(brands);
const crawlerData = ref({});
const loading = ref(false);
const crawlerResult = ref(false);
const years = [
    2023, 2022, 2021, 2020, 2019, 2018, 2017, 2016, 2015, 2014, 2013, 2012,
    2011, 2010, 2009, 2008, 2007, 2006, 2005, 2004, 2003, 2002, 2001, 2000,
];

const form = useForm({
    brand: null,
    model: null,
    year: null,
});

const filteredBrands = computed(() =>
    queryBrands.value === ""
        ? brandsArray
        : brandsArray.filter((brand) => {
              return brand
                  .toLowerCase()
                  .includes(queryBrands.value.toLowerCase());
          })
);

const filteredModels = computed(() =>
    queryModels.value === ""
        ? brands[form.brand]
        : brands[form.brand].filter((item) => {
              return item
                  .toLowerCase()
                  .includes(queryModels.value.toLowerCase());
          })
);

const filteredYears = computed(() =>
    queryYears.value === ""
        ? years
        : years.filter((item) => {
              return item
                  .toLowerCase()
                  .includes(queryYears.value.toLowerCase());
          })
);

const submit = () => {
    loading.value = true;
    crawlerResult.value = false;
    axios
        .post(route("form.submit"), form)
        .then((result) => {
            crawlerData.value = {
                sum: result.data.sum,
                totalCars: result.data.totalCars,
                url: result.data.url,
                brand: result.data.brand,
                model: result.data.model,
                year: result.data.year,
            };
        })
        .catch((e) => console.log(e))
        .finally(() => {
            loading.value = false;
            crawlerResult.value = true;
        });
};
</script>

<template>
    <Head title="Welcome" />

    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-[#212A3E]"
    >
        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <h1 class="text-xl text-center font-bold text-color">
                Vrei să vinzi sau să cumperi o mașină second hand? Află prețul
                mediu al mașinilor de pe autovit.
            </h1>
            <h2 class="text-lg font-semibold text-center mt-3 text-[#9BA4B5]">
                Selecteză marca, brandul mașinii și anul
            </h2>
            <form
                @submit.prevent="submit"
                class="mt-8 flex flex-col items-around"
            >
                <div
                    class="flex flex-col sm:flex-row sm:flex-wrap justify-between"
                >
                    <div class="flex flex-col">
                        <div class="relative flex items-center">
                            <Combobox v-model="form.brand">
                                <ComboboxLabel
                                    class="text-color text-lg mr-4 w-12"
                                    >Marca</ComboboxLabel
                                >
                                <ComboboxInput
                                    class="rounded"
                                    required
                                    @change="queryBrands = $event.target.value"
                                    :displayValue="(brand) => brand"
                                />

                                <ComboboxButton
                                    class="w-6 text-[#212A3E] absolute right-1 z-48"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        strokeWidth="{1.5}"
                                        stroke="currentColor"
                                        className="w-6 h-6"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3"
                                        />
                                    </svg>
                                </ComboboxButton>

                                <transition
                                    enter-active-class="transition duration-100 ease-out"
                                    enter-from-class="transform scale-95 opacity-0"
                                    enter-to-class="transform scale-100 opacity-100"
                                    leave-active-class="transition duration-75 ease-out"
                                    leave-from-class="transform scale-100 opacity-100"
                                    leave-to-class="transform scale-95 opacity-0"
                                >
                                    <ComboboxOptions
                                        class="absolute top-12 left-16 z-50 max-h-64 w-[198px] overflow-y-auto bg-[#394867] rounded-lg"
                                    >
                                        <ComboboxOption
                                            v-for="(
                                                brand, index
                                            ) in filteredBrands"
                                            :key="index"
                                            :value="brand"
                                            class="py-1 px-3 hover:backdrop-brightness-125 hover:cursor-pointer w-full"
                                        >
                                            <li class="'bg-blue-500 text-white">
                                                {{ brand }}
                                            </li>
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </transition>
                            </Combobox>
                        </div>
                        <p
                            class="mt-2 text-red-500 max-w-[250px]"
                            v-show="form.errors.brand"
                        >
                            {{ form.errors.brand }}
                        </p>
                    </div>

                    <div class="flex flex-col mt-4 sm:mt-0">
                        <div class="relative flex items-center">
                            <Combobox v-model="form.model">
                                <ComboboxLabel
                                    class="text-color text-lg mr-4 w-12"
                                >
                                    Model
                                </ComboboxLabel>
                                <ComboboxInput
                                    required
                                    class="rounded"
                                    @change="queryModels = $event.target.value"
                                    :displayValue="(modelul) => modelul"
                                    :disabled="!form.brand"
                                />
                                <ComboboxButton
                                    class="w-6 text-[#212A3E] absolute right-1 z-48"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        strokeWidth="{1.5}"
                                        stroke="currentColor"
                                        className="w-6 h-6"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3"
                                        />
                                    </svg>
                                </ComboboxButton>
                                <transition
                                    enter-active-class="transition duration-100 ease-out"
                                    enter-from-class="transform scale-95 opacity-0"
                                    enter-to-class="transform scale-100 opacity-100"
                                    leave-active-class="transition duration-75 ease-out"
                                    leave-from-class="transform scale-100 opacity-100"
                                    leave-to-class="transform scale-95 opacity-0"
                                >
                                    <ComboboxOptions
                                        v-show="form.brand && filteredModels"
                                        class="absolute top-12 left-16 z-50 max-h-64 w-[198px] overflow-y-auto bg-[#394867] rounded-lg"
                                    >
                                        <ComboboxOption
                                            v-for="(
                                                item, index
                                            ) in filteredModels"
                                            :key="index"
                                            :value="item"
                                            class="py-1 px-3 hover:backdrop-brightness-125 hover:cursor-pointer w-full"
                                        >
                                            <li class="'bg-blue-500 text-white">
                                                {{ item }}
                                            </li>
                                        </ComboboxOption>
                                    </ComboboxOptions>
                                </transition>
                            </Combobox>
                        </div>
                        <p
                            class="mt-2 text-red-500 max-w-[250px]"
                            v-show="form.errors.model"
                        >
                            {{ form.errors.model }}
                        </p>
                    </div>
                    <div class="flex flex-col mt-4 md:mt-0">
                        <div class="flex flex-col">
                            <div class="relative flex items-center">
                                <Combobox v-model="form.year">
                                    <ComboboxLabel
                                        class="text-color text-lg mr-4 w-12"
                                    >
                                        An
                                    </ComboboxLabel>
                                    <ComboboxInput
                                        required
                                        class="rounded"
                                        @change="
                                            queryYears = $event.target.value
                                        "
                                        :displayValue="(year) => year"
                                    />
                                    <ComboboxButton
                                        class="w-6 text-[#212A3E] absolute right-1 z-48"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            strokeWidth="{1.5}"
                                            stroke="currentColor"
                                            className="w-6 h-6"
                                        >
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3"
                                            />
                                        </svg>
                                    </ComboboxButton>
                                    <transition
                                        enter-active-class="transition duration-100 ease-out"
                                        enter-from-class="transform scale-95 opacity-0"
                                        enter-to-class="transform scale-100 opacity-100"
                                        leave-active-class="transition duration-75 ease-out"
                                        leave-from-class="transform scale-100 opacity-100"
                                        leave-to-class="transform scale-95 opacity-0"
                                    >
                                        <ComboboxOptions
                                            class="absolute top-12 left-16 z-48 max-h-64 w-[198px] overflow-y-auto bg-[#394867] rounded-lg"
                                        >
                                            <ComboboxOption
                                                v-for="(
                                                    item, index
                                                ) in filteredYears"
                                                :key="index"
                                                :value="item"
                                                class="py-1 px-3 hover:backdrop-brightness-125 hover:cursor-pointer w-full"
                                            >
                                                <li
                                                    class="'bg-blue-500 text-white"
                                                >
                                                    {{ item }}
                                                </li>
                                            </ComboboxOption>
                                        </ComboboxOptions>
                                    </transition>
                                </Combobox>
                            </div>
                            <p
                                class="mt-2 text-red-500 max-w-[250px]"
                                v-show="form.errors.year"
                            >
                                {{ form.errors.year }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-center mt-8">
                    <button
                        type="submit"
                        :disabled="form.processing || loading"
                        class="rounded-md bg-[#9BA4B5] px-3 py-2 text-lg font-semibold text-[#394867] shadow-sm hover:brightness-125"
                    >
                        Caută
                    </button>
                </div>
            </form>
            <div
                class="mt-16 flex justify-center items-center"
                v-show="loading"
            >
                <svg
                    aria-hidden="true"
                    class="inline w-10 h-10 mr-2 text-gray-200 animate-spin dark:text-gray-600 fill-blue-600"
                    viewBox="0 0 100 101"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                        fill="currentColor"
                    />
                    <path
                        d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                        fill="currentFill"
                    />
                </svg>
            </div>
            <div
                class="mt-16 flex justify-center items-center"
                v-show="crawlerResult"
            >
                <table
                    v-if="crawlerData.totalCars > 0"
                    class="text-color border-2 border-[#9BA4B5]"
                >
                    <thead class="border-b border-[#9BA4B5]">
                        <tr>
                            <th class="border-r border-[#9BA4B5] py-2 px-3">
                                Marca
                            </th>
                            <th class="border-r border-[#9BA4B5] py-2 px-3">
                                Model
                            </th>
                            <th class="border-r border-[#9BA4B5] py-2 px-3">
                                An Fabricație
                            </th>
                            <th class="border-r border-[#9BA4B5] py-2 px-3">
                                Număr Mașini
                            </th>
                            <th class="border-r border-[#9BA4B5] py-2 px-3">
                                Preț mediu
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border-r border-[#9BA4B5] py-2 px-3">
                                {{ crawlerData.brand }}
                            </td>
                            <td class="border-r border-[#9BA4B5] py-2 px-3">
                                {{ crawlerData.model }}
                            </td>
                            <td class="border-r border-[#9BA4B5] py-2 px-3">
                                {{ crawlerData.year }}
                            </td>
                            <td class="border-r border-[#9BA4B5] py-2 px-3">
                                {{ crawlerData.totalCars }}
                            </td>
                            <td class="border-r border-[#9BA4B5] py-2 px-3">
                                {{
                                    Math.round(
                                        crawlerData.sum / crawlerData.totalCars
                                    )
                                }}€
                            </td>
                            <td class="border-r border-[#9BA4B5] py-2 px-3">
                                <a :href="crawlerData.url" target="_blank"
                                    >Vezi mașinile</a
                                >
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-else>
                    <p class="text-lg text-red-500">Doesn't exist</p>
                </div>
            </div>
        </div>
    </div>
</template>

<style></style>
