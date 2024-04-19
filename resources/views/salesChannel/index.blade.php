<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>sales Channels</h1>
    <div class="mt-[1.9rem]">
        <button type="button" class="w-[18.81rem] h-[2.5rem] hover:bg-gray-100 rounded-md text-[14px] gap-[0.43rem] flex items-center justify-center" style="border: 1px solid #717172">
            <img src="../images/edit-icon.png" alt="show hide icon">
            <p class="text-[14px] text-[#717171]">Toon gekoppelde verkoop kanalen</p>
        </button>
    </div>
    <ul>
        @foreach ($salesChannels as $salesChannel)
        <li>
            <a href="/saleschannels/{{$salesChannel->id}}">{{$salesChannel->name}}</a>
        </li>
        @endforeach
    </ul>
   
</body>
</html>