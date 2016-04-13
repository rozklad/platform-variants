<script type="text/template" data-grid="variant" data-template="results">

	<% _.each(results, function(r) { %>

		<tr data-grid-row>
			<td><input content="id" input data-grid-checkbox="" name="entries[]" type="checkbox" value="<%= r.id %>"></td>
			<td><a href="<%= r.edit_uri %>" href="<%= r.edit_uri %>"><%= r.id %></a></td>
			<td><%= r.slug %></td>
			<td><%= r.code %></td>
			<td><%= r.ean %></td>
			<td><%= r.weight %></td>
			<td><%= r.stock %></td>
			<td><%= r.parent_id %></td>
			<td><%= r.created_at %></td>
		</tr>

	<% }); %>

</script>
