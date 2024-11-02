<div x-data="{
    countries: [
        { code: 'it', name: 'Italy', img: 'https://via.placeholder.com/20?text=IT' },
        { code: 'fr', name: 'France', img: 'https://via.placeholder.com/20?text=FR' },
        { code: 'uk', name: 'United Kingdom', img: 'https://via.placeholder.com/20?text=UK' }
    ],
    myCountryCode: ''
}">
    <select x-model="myCountryCode">
        <option value="" disabled>Select a country</option>
        <template x-for="(country, i) in countries" :key="i">
            <option :value="country.code">
                <img src="country.img" class="inline-block w-4 h-4 mr-2" alt="" />
                <span x-text="country.name"></span>
            </option>
        </template>
    </select>

    <p>My Country Code: <span x-text="myCountryCode"></span></p>
    <p>(expected output: it, fr, uk, ...)</p>
    <br>
    <button @click="myCountryCode='it'">Set: it</button>
    <button @click="myCountryCode='fr'">Set: fr</button>
    <button @click="myCountryCode='uk'">Set: uk</button>
    <br>
</div>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
