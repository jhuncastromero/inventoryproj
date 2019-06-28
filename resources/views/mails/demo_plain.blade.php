Hello  {{ $demo->receiver }},
This is a demo email for testing purposes! also, it's the HTML Version. 

Demo object values:
Demo One: {{$demo->demo_one }}
Demo Two: {{$demo->demo_two }}


Values passed by With method:

testVarOne: {{ $testVarOne }}
testVarTwo: {{ $testVarTwo }}


Thank you,
{{ $demo->sender }}