<template>
    <div class="m-8 p-6 bg-grey rounded shadow">
        <div class="flex">
            <div class="flex">
                <div class="p-6">
                    <div>
                        <table class="w-full text-left table-collapse">
                            <thead>
                            <tr>
                                <th colspan="7" class="text-sm font-semibold text-grey-darker p-2 bg-grey-lightest">League Table</th>
                            </tr>
                            <tr>
                                <th class="text-sm font-semibold text-grey-darker p-2 bg-grey-lightest">Teams</th>
                                <th class="text-sm font-semibold text-grey-darker p-2 bg-grey-lightest">PTS</th>
                                <th class="text-sm font-semibold text-grey-darker p-2 bg-grey-lightest">P</th>
                                <th class="text-sm font-semibold text-grey-darker p-2 bg-grey-lightest">W</th>
                                <th class="text-sm font-semibold text-grey-darker p-2 bg-grey-lightest">D</th>
                                <th class="text-sm font-semibold text-grey-darker p-2 bg-grey-lightest">L</th>
                                <th class="text-sm font-semibold text-grey-darker p-2 bg-grey-lightest">GD</th>
                            </tr>
                            </thead>
                            <tbody class="align-baseline">
                            <tr v-for="team in teams">
                                <td class="p-2 border-t border-grey-light font-mono text-xs text-purple-dark whitespace-no-wrap">{{ team.name }}</td>
                                <td class="p-2 border-t border-grey-light font-mono text-xs text-purple-dark whitespace-no-wrap">{{team.PTS }}</td>
                                <td class="p-2 border-t border-grey-light font-mono text-xs text-purple-dark whitespace-no-wrap">{{ team.P }}</td>
                                <td class="p-2 border-t border-grey-light font-mono text-xs text-purple-dark whitespace-no-wrap">{{ team.W }}</td>
                                <td class="p-2 border-t border-grey-light font-mono text-xs text-purple-dark whitespace-no-wrap">{{ team.D }}</td>
                                <td class="p-2 border-t border-grey-light font-mono text-xs text-purple-dark whitespace-no-wrap">{{ team.L }}</td>
                                <td class="p-2 border-t border-grey-light font-mono text-xs text-purple-dark whitespace-no-wrap">{{ team.GD }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-between">
                        <button @click="play" class="bg-blue hover:bg-blue-dark text-white font-bold py-2 px-4 rounded">Play All</button>
                    </div>
                </div>
                <div class="p-6">
                    <table class="w-full text-left table-collapse">
                        <thead>
                        <tr>
                            <th class="text-sm font-semibold text-grey-darker p-2 bg-grey-lightest">Match Results</th>
                        </tr>
                        </thead>
                        <tbody class="align-baseline">
                        <tr>
                            <td class="p-2 border-t border-grey-light font-mono text-xs text-blue-dark whitespace-pre">{{ week }} Week Match Result</td>
                        </tr>
                        <tr>
                            <td class="p-2 border-t border-grey-light font-mono text-xs text-purple-dark whitespace-no-wrap">
                                <div v-for="match in matches" class="flex justify-between">
                                    <div class="px-2 text-right">{{ match.home.name }}</div>
                                    <div class="text-center p-2 self-center">{{ match.home_goals }} - {{ match.away_goals }}</div>
                                    <div class="px-2 text-left">{{ match.away.name }}</div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="p-6">
                <table class="w-full text-left table-collapse">
                    <thead>
                    <tr>
                        <th colspan="2" class="text-sm font-semibold text-grey-darker p-2 bg-grey-lightest">Prediction for {{ week + 1 }} week</th>
                    </tr>
                    </thead>
                    <tbody class="align-baseline">
                    <tr v-for="team in teams">
                        <td class="p-2 border-t border-grey-light font-mono text-xs text-blue-dark whitespace-pre">{{ team.name }}</td>
                        <td class="p-2 border-t border-grey-light font-mono text-xs text-purple-dark whitespace-no-wrap">{{ team.winning_prediction.toLocaleString() }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default{
        data() {
            return {
                teams: [],
                matches: [],
                week: 0,
            }
        },

        methods: {
            loadTeams() {
                axios.get('/api/league')
                    .then(response => {
                        this.teams = response.data;
                    })
                    .catch(errors => {
                        console.log(errors);
                    })
            },

            play() {
                axios.post('/api/play', {})
                    .then(response => {
                        this.matches = response.data;
                        this.loadTeams();
                        this.week = this.matches[0].week;
                    })
                    .catch(errors => {
                        console.log(errors);
                    })
            }
        },

        created() {
            this.loadTeams();
        }
    }
</script>