<label class="form-label" for="select2-basic">Services</label>
<select class="select2 form-select" name="services" id="select2-services">
    <option value="0"> ---- Choose Service ---- </option>

    @if (isset($data['services_list']) && count($data['services_list'])>0)

        @foreach ($data['services_list'] as $key => $value)
            <option value="{{$value['id']}}">{{$value['service_name']}}</option>
        @endforeach

    @endif

 </select>