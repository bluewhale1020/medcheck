<table>
    <thead>
      <tr>
      @foreach ($columns as $column)
      <th>{{ $column }}</th>
      @endforeach
      </tr>                 
    </thead>
    <tbody>
        @foreach ($sortedData as $record)
        <tr>
            @foreach ($record as $key => $val)
                <td>{{ $val }}</td>
            @endforeach
        </tr>
            
        @endforeach
  </tbody></table>