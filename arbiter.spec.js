jasmine.DEFAULT_TIMEOUT_INTERVAL = 50;

describe( 'this.arbiter', function() {

  beforeEach( function() {
    this.arbiter = Arbiter.create();
  } );

  describe( 'create', function() {

    it( 'does not publish to the orginal instance', function() {
      var
        spy1 = jasmine.createSpy(),
        spy2 = jasmine.createSpy();

      Arbiter.subscribe( 'msg', spy1 );
      this.arbiter.subscribe( 'msg', spy2 );

      this.arbiter.publish( 'msg' );

      expect( spy1 ).not.toHaveBeenCalled();
      expect( spy2 ).toHaveBeenCalled();

    } );

  } );

  describe( 'publish', function() {

    it( 'publishes simple messages synchronously', function() {
      this.arbiter.publish( 'component/msg' );

      // The above statement with throw and this will not execute
      expect( 1 ).toBe( 1 );
    } );

    it( 'returns true if no subscribes throw', function() {
      expect( this.arbiter.publish( 'msg' ) ).toBe( true );
    } );

    it( 'returns false if a subscriber throws', function() {
      this.arbiter.subscribe( 'msg', function() {
          throw new Error( 'error' );
      } );

      expect( this.arbiter.publish( 'msg' ) ).toBe( false );
    } );

    it( 'does not publish to other topics', function() {
      var spy = jasmine.createSpy();

      this.arbiter.subscribe( 'component', spy );
      this.arbiter.publish( 'component/one' );

      expect( spy ).not.toHaveBeenCalled();
    } );

    it( 'invokes subscription functions', function() {
      var spy = jasmine.createSpy( 'subscription-invoker' );
      this.arbiter.subscribe( 'component/msg', spy );
      this.arbiter.publish( 'component/msg' );

      expect( spy ).toHaveBeenCalled();
    } );

    it( 'provides the subscription published_data', function() {
      var
        spy = jasmine.createSpy( 'subscription-invoker' ),
        data = {};

      this.arbiter.subscribe( 'component/msg', spy );
      this.arbiter.publish( 'component/msg', data );

      expect( spy.calls.count() ).toBe( 1 );
      expect( spy.calls.first().args[ 0 ] ).toEqual( data );
    } );

    it( 'provides the subscription the message', function() {
      var
        spy = jasmine.createSpy( 'subscription-invoker' ),
        topic = 'component/msg';

      this.arbiter.subscribe( 'component/msg', spy );
      this.arbiter.publish( 'component/msg' );

      expect( spy.calls.count() ).toBe( 1 );
      expect( spy.calls.first().args[ 1 ] ).toEqual( topic );
    } );

    it( 'provides the subscription subscriber_data', function() {
      var spy = jasmine.createSpy( 'subscription-invoker' );

      this.arbiter.subscribe( 'component/msg', spy );
      this.arbiter.publish( 'component/msg' );

      expect( spy.calls.count() ).toBe( 1 );
      expect( typeof spy.calls.first().args[ 2 ] ).toEqual( 'object' );
    } );

    it( 'allows for forcing message bubbling', function() {
      var
        spy0 = jasmine.createSpy( 'lower priority' ),
        spy1 = jasmine.createSpy( 'higher priority' ).and.returnValue( false );

      this.arbiter.subscribe( 'component/msg', spy0 );
      this.arbiter.subscribe( 'component/msg', { priority: 1 }, spy1 );
      this.arbiter.publish( 'component/msg', null, { cancelable: false } );

      expect( spy0 ).toHaveBeenCalled();
      expect( spy1 ).toHaveBeenCalled();
    } );

    it( 'allows late subscribers to be notified of past messages', function() {
      var spy = jasmine.createSpy();

      this.arbiter.publish( 'component/msg', null, { persist: true } );
      this.arbiter.subscribe( 'component/msg', spy );

      expect( spy ).toHaveBeenCalled();
    } );

    xit( 'allows late subscribers to ignore persisted message', function() {
      var spy = jasmine.createSpy();

      this.arbiter.publish( 'msg', null, { persist: true } );
      this.arbiter.subscribe( 'msg', { persist: false }, spy );

      expect( spy ).not.toHaveBeenCalled();
    } );

    it( 'allows for invoking subscriptions asynchronously', function( done ) {
      var spy = jasmine.createSpy().and.callFake( function() {
        done();
      } );

      this.arbiter.subscribe( 'component/msg', spy );
      this.arbiter.publish( 'component/msg', null, { async: true } );
      expect( spy ).not.toHaveBeenCalled();

    } );

  } );

  describe( 'subscribe', function() {

    it( 'allows for multiple subscriptions seperated by commas', function() {
      var spy = jasmine.createSpy();

      this.arbiter.subscribe( 'component/msg, component/msg2', spy );
      this.arbiter.publish( 'component/msg' );

      expect( spy ).toHaveBeenCalled();
      spy.calls.reset();
      expect( spy ).not.toHaveBeenCalled();

      this.arbiter.publish( 'component/msg2' );
      expect( spy ).toHaveBeenCalled();
    } );

    it( 'allows for passing an array of message topics', function() {
      var spy = jasmine.createSpy();

      this.arbiter.subscribe( [ 'component/msg', 'component/msg2' ], spy );
      this.arbiter.publish( 'component/msg' );

      expect( spy ).toHaveBeenCalled();

      this.arbiter.publish( 'component/msg2' );
      expect( spy.calls.count() ).toBe( 2 );
    } );

    it( 'can use a wildcard subscribe to multiple messages', function() {
      var spy = jasmine.createSpy();

      this.arbiter.subscribe( 'component/*', spy );
      this.arbiter.publish( 'component/one' );

      expect( spy ).toHaveBeenCalled();

      this.arbiter.publish( 'component/two' );

      expect( spy.calls.count() ).toBe( 2 );
    } );

    it( 'can subscribe to all messages', function() {
      var spy = jasmine.createSpy();

      this.arbiter.subscribe( '*', spy );

      this.arbiter.publish( 'component/msg1' );
      this.arbiter.publish( 'component/msg2' );
      this.arbiter.publish( 'component' );
      this.arbiter.publish( '' );

      expect( spy.calls.count() ).toBe( 4 );
    } );

    it( 'invokes higher priority subscribers before others', function() {
      var
        spy0 = jasmine.createSpy( 'lower priority' ),
        spy1 = jasmine.createSpy( 'high priority' ).and.callFake( function() {
          expect( spy0 ).not.toHaveBeenCalled();
        } );

      this.arbiter.subscribe( 'component/msg', spy0 );
      this.arbiter.subscribe( 'component/msg', { priority: 1 }, spy1 );
      this.arbiter.publish( 'component/msg' );

      expect( spy0 ).toHaveBeenCalled( );
      expect( spy1 ).toHaveBeenCalled( );
    } );

    it( 'allows returning false to prevent subsequent subscriber invocations', function() {
      var
        spy0 = jasmine.createSpy( 'lower priority' ),
        spy1 = jasmine.createSpy( 'high priority' ).and.returnValue( false );

      this.arbiter.subscribe( 'component/msg', spy0 );
      this.arbiter.subscribe( 'component/msg', { priority: 1 }, spy1 );
      this.arbiter.publish( 'component/msg' );

      expect( spy0 ).not.toHaveBeenCalled();
      expect( spy1 ).toHaveBeenCalled();
    } );

    it( 'can specify a subscribe be always exectued asynchronously', function( done ) {
      var spy = jasmine.createSpy().and.callFake( function() {
        done();
      } );

      this.arbiter.subscribe( 'msg', { async: true }, spy );
      this.arbiter.publish( 'msg' );

      expect( spy ).not.toHaveBeenCalled();
    } );

    it( 'can specify the value of `this`', function() {
      var context = {};

      this.arbiter.subscribe( 'msg', null, context, function() {
        expect( this ).toBe( context );
      } );
      this.arbiter.publish( 'msg' );
    } );

  } );

  describe( 'unsubscribe', function() {

    it( 'doesn\'t invoke subscriptions after unsubscribing', function() {
      var
        spy = jasmine.createSpy(),
        id = this.arbiter.subscribe( 'msg', spy );

      this.arbiter.unsubscribe( id );
      this.arbiter.publish( 'msg' );

      expect( spy ).not.toHaveBeenCalled();
    } );

  } );

  describe( 'resubscribe', function() {

    it( 'doesn\'t invoke subscriptions after unsubscribing', function() {
      var
        spy = jasmine.createSpy(),
        id = this.arbiter.subscribe( 'msg', spy );

      this.arbiter.unsubscribe( id );
      this.arbiter.resubscribe( id );
      this.arbiter.publish( 'msg' );

      expect( spy ).toHaveBeenCalled();
    } );

  } );

  describe( 'guard against regressions', function() {

    it( 'invokes higher priority wildcard subscriptions before lower priority non-wildcard #3', function() {
      var
        spy1 = jasmine.createSpy().and.callFake( function() {
          expect( spy2 ).not.toHaveBeenCalled();
        } ),
        spy2 = jasmine.createSpy();

      this.arbiter.subscribe( 'msg/*', { priority: 1 }, spy1 );
      this.arbiter.subscribe( 'msg/name', spy2 );
      this.arbiter.publish( 'msg/name' );

      expect( spy1 ).toHaveBeenCalled();
      expect( spy2 ).toHaveBeenCalled();
    } );

  } );

} );
