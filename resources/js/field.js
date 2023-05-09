import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'
import FilterField from './components/FilterField'

Nova.booting((app, store) => {
    app.component('index-nova-boolean-group-field', IndexField)
    app.component('detail-nova-boolean-group-field', DetailField)
    app.component('form-nova-boolean-group-field', FormField)
    app.component('filter-nova-boolean-group-field', FilterField)
})
