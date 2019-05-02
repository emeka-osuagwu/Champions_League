<template>
    <div>
        <nav class="navbar navbar-light bg-light">
            <a class="navbar-brand" href="#">
                <img src="/docs/4.3/assets/brand/bootstrap-solid.svg" width="30" height="30" alt="" />
            </a>
        </nav>

        <div class="" style="padding: 30px;">
            <div class="row">
                <div class="col">
                    <table class="table">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">Teams</th>
                          <th scope="col">PTS</th>
                          <th scope="col">P</th>
                          <th scope="col">W</th>
                          <th scope="col">D</th>
                          <th scope="col">L</th>
                          <th scope="col">GD</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="team in teams">
                          <th scope="row">{{ team.name }}</th>
                          <td>{{team.PTS }}</td>
                          <td>{{team.P}}</td>
                          <td>{{team.W}}</td>
                          <td>{{team.D}}</td>
                          <td>{{team.L}}</td>
                          <td>{{team.GD}}</td>
                        </tr>
                      </tbody>
                    </table>
                </div>
                <div class="col">
                    <table class="table">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">Match Results</th>
                          <th scope="col"></th>
                          <th scope="col"></th>
                        </tr>
                      </thead>
                      <tbody>
                        <p>{{ week }} Week Match Result</p>
                        <tr v-for="match in matches">
                          <th scope="row">1</th>
                          <td>{{ match.home.name }}</td>
                          <td>{{ match.home_goals }} - {{ match.away_goals }}</td>
                          <td>{{ match.away.name }}</td>
                        </tr>
                      </tbody>
                    </table>
                </div>


                <div class="col">
                    <table class="table">
                      <thead class="thead-dark">
                        <tr>
                          <th scope="col">Prediction for {{ week + 1 }} week</th>
                          <th scope="col"></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="team in teams">
                          <th scope="row">{{ team.name }}</th>
                          <td>{{ team.winning_prediction.toLocaleString() }}</td>
                        </tr>
                      </tbody>
                    </table>
                </div>


            </div>
            <button type="button" @click="play" class="btn btn-primary">Pay All</button>
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