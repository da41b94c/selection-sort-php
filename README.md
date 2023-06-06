# PHP: Алгоритм сортировка выбором
Сортировка массива с помощью алгоритма «Сортировка выбором».
Сложность: O(n log n)
<pre>
$sampleList = [ 23, 42, 8, 4, 15, 16 ];

echo '&lt;pre&gt;', var_dump( findMin( $sampleList ) ) ,'&lt;/pre&gt;';
// &gt; 3

echo '&lt;pre&gt;', var_dump( selectionSort( $sampleList ) ) ,'&lt;/pre&gt;';
// &gt; array(6) {
// &gt;   [0]=&gt;
// &gt;   int(4)
// &gt;   [1]=&gt;
// &gt;   int(8)
// &gt;   [2]=&gt;
// &gt;   int(15)
// &gt;   [3]=&gt;
// &gt;   int(16)
// &gt;   [4]=&gt;
// &gt;   int(23)
// &gt;   [5]=&gt;
// &gt;   int(42)
// &gt; }

</pre>
