<template>
  <main class="container">
    <div class="row">
    <div class="col-6">
      <h1 class="text-left">Simple Todo App</h1>
      <div class="row">
        <div class="col-12">
          <form class="form-horizontal" v-on:submit.prevent="onSubmit()">
            <div class="form-group">
              <label for="todo" class="sr-only">What to do?</label>
              <input v-model="todo" type="text" class="form-control" id="todo" placeholder="What to do today?" autofocus>
            </div>
            <div id="listing">
              <p v-if="msg" class="alert alert-success alert-dismissible fade show" role="alert">
                {{msg}}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>          
              </p>
              <table class="table">
                <thead>
                  <tr>
                    <th>Activity</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(list, idx) in lists.data" :key="idx">
                    <td>
                      <span v-if="list.is_finished == 1">
                        <s>{{list.title}}</s>
                      </span>
                      <span v-else>{{list.title}}</span>
                    </td>
                    <td>
                      <div class="btn-group">
                      <div v-if="list.is_finished == 1">
                        <button type="button" v-on:click.prevent="markAsUnDone(list)" class="btn btn-info"><i class="fas fa-check-circle"></i> Mark as undone</button>
                      </div>
                      <div v-else>
                        <button type="button" v-on:click.prevent="markAsDone(list)" class="btn btn-success"><i class="fas fa-check-circle"></i> Mark as done</button>
                      </div>
                        <button type="button" v-on:click.prevent="deleteTodo(list)" class="btn btn-danger"><i class="fa fa-trash"></i>Delete todo</button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
              <p v-if="lists.length === 0" class="alert alert-warning">Nothing to do yet, add something!</p>
            </div>
          </form>
          </div> 
        </div>
    </div>
    <div class="col-6">
      <h1 class="text-left">Simple Domain Check Tool</h1>
      <div class="row">
        <div class="col-12">
          <form class="form-horizontal" v-on:submit.prevent="onSearch()">
            <div class="form-group">
              <div class="input-group">
                <input v-model="domain" type="text" class="form-control" placeholder="Type a domain: eg. google.com">
                <button type="submit" class="btn btn-primary">Search</button>
              </div>
              <hr>
              <p class="alert alert-info" v-if="searching">Searching ...</p>
              <div class="form-group" v-if="searchResult && !searching">
                  <h3 class="text-left">Search Result:</h3>
                  <ul class="list-unstyled float-left">
                    <li v-for="(result, idx) in searchResult" :key="idx"><span class="pull-left">{{idx}}:</span> &nbsp; <strong>{{result}}</strong> </li>
                  </ul>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    </div>
  </main>
</template>

<script>
  export default {
    data () {
      return {
        msg: null,
        todo: null,
        lists: [],
        domain: null,
        searchResult: null,
        searching: false
      }
    },
    created () {
      this.listTodo()
    },
    methods: {
      listTodo: function () {
        return this.$http.get('http://127.0.0.1:8000/api/todo').then((response) => {
          this.lists = response.data
      })
      },
      onSubmit: function () {
        this.$http.post('http://127.0.0.1:8000/api/todo', {
          title: this.todo
        }).then(() => {
          this.listTodo()
          this.todo = null
        })
      },
      markAsDone: function(list) {
        this.$http.put('http://127.0.0.1:8000/api/todo/' + list.id, {
          is_finished: 1
        }).then(() => {
          this.listTodo()
          this.msg = 'Todo is now done.'
        })
      },
      markAsUnDone: function(list) {
        this.$http.put('http://127.0.0.1:8000/api/todo/' + list.id, {
          is_finished: 0
        }).then(() => {
          this.listTodo()
          this.msg = 'Todo has been undone.'
        })
      },
      deleteTodo: function(list) {
        if(confirm("Are you sure you really want to delete?")){
          this.$http.delete('http://127.0.0.1:8000/api/todo/' + list.id)
            .then(() => {
              this.listTodo().splice(list.id, 1)
          })
          this.msg = 'Todo deleted!'
        }
      },
      onSearch: function() {
        this.searching = true;
        this.$http.post('http://127.0.0.1:8000/api/search', {
          domain: this.domain
        }).then((response) => {
          this.searching = false;
          this.searchResult = response.data.DomainInfo
          this.domain = null
        })
      }
    }
  }
</script>
