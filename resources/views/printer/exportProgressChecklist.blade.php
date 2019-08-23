{{-- {{ HTML::style('css/app.css') }} --}}
{{-- <link rel="stylesheet" href="{{ asset('/css/app.css') }}"> --}}
<table>
    <thead>
      <tr>      
      <th>進捗度</th>
      <th>通番</th>
      <th>フリガナ</th>
      <th>生年月日</th>
      <th>性別</th>
      @foreach ($columns as $column)
      <th>{{ $column }}</th>
      @endforeach
      </tr>                 
    </thead>
    <tbody>
        @foreach ($reserveInfos as $reserve)
        <tr>
                <td>{{ $reserve->progress }}</td>
                <td>{{ $reserve->serial_number }}</td>
                <td>{{ $reserve->account->kana }}</td>
                <td>{{ $reserve->account->birthdate }}</td>
                <td>{{ $reserve->account->sex }}</td>
            @foreach ($reserve->select_item->getAttributes() as $key => $val)
                @if ($val == 1)
                <td style="color: red;">●</td>
                @elseif ($val == 2)
                <td style="color: green;">✔</td>
                @elseif ($val == 3)
                <td style="color: yellow;">△</td>
                @else
                <td></td>
                @endif
            @endforeach
        </tr>            
        @endforeach
  </tbody></table>