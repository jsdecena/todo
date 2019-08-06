<template>
  <main class="container">
    <h1>My Simple Todo App</h1>
    <form class="form-horizontal" v-on:submit.prevent="onSubmit()">
      <div class="form-group">
        <label for="todo" class="sr-only">What to do?</label>
        <input v-model="todo" type="text" class="form-control" id="todo" placeholder="What to do today?">
      </div>
      <div id="listing">
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
                  <button type="button" v-on:click.prevent="markAsUnDone(list)" class="btn btn-info"><i class="fa fa-check"></i> Mark as undone</button>
                </div>
                <div v-else>
                  <button type="button" v-on:click.prevent="markAsDone(list)" class="btn btn-success"><i class="fa fa-check"></i> Mark as done</button>
                </div>
                  <button type="button" class="btn btn-danger"><i class="fa fa-trash"></i>Delete todo</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <p v-if="lists.length === 0" class="alert alert-warning">Nothing to do yet, add something!</p>
      </div>
    </form>
  </main>
</template>

<script>
  export default {
    data () {
      return {
        todo: null,
        lists: []
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
        }).then((response) => {
          this.listTodo()
          this.todo = null
        })
      },
      markAsDone: function(list) {
        this.$http.put('http://127.0.0.1:8000/api/todo/' + list.id, {
          is_finished: 1
        }).then((response) => {
          this.listTodo()
          this.todo = null
        })
      },
      markAsUnDone: function(list) {
        this.$http.put('http://127.0.0.1:8000/api/todo/' + list.id, {
          is_finished: 0
        }).then((response) => {
          this.listTodo()
          this.todo = null
        })
      }      
    }
  }
</script>
