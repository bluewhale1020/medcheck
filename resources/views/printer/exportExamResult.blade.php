<table>
    <thead>
      <tr>
      <th>健診ID</th>
      <th>通番</th>
      <th>在籍番号</th>
      <th>受診日</th>
      <th>フリガナ</th>
      <th>生年月日</th>
      <th>聴力検査方法</th>
      @foreach ($columns as $column)
      <th>{{ $column }}</th>
      @endforeach
      </tr>                 
    </thead>
    <tbody>
        
        @foreach ($reserveInfos as $reserve)
        <tr>
                <td>{{ $reserve->checkup_info_id }}</td>
                <td>{{ $reserve->serial_number }}</td>
                <td>{{ $reserve->account->id_number }}</td>
                <td>{{ \Carbon\Carbon::parse($reserve->checkup_date)->format('Y-m-d')}}</td>
                <td>{{ $reserve->account->kana }}</td>
                <td>{{ $reserve->account->birthdate }}</td>
                <td>{{ $reserve->select_item->audiometry_method }}</td>
                @if (!empty($reserve->exam_result))
                    @foreach ($reserve->exam_result->getAttributes() as $key => $val)
                        @if (!in_array($key,['id','reserve_info_id','is_hungry','hours_after_meals','created_at', 'updated_at']))
                        <td>
                            @if (is_numeric($val) and !empty($decimal_places[$key]))
                                {{  (string) number_format($val,$decimal_places[$key]) }}
                            @else
                                {{ $val }}
                            
                            @endif
                        </td>
                        @endif
                    @endforeach
                @endif
        </tr>
            
        @endforeach
  </tbody></table>